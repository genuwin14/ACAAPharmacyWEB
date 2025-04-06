<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProductModel;
use App\Models\CheckoutModel;

class Admin extends Controller
{
    public function index()
    {
        return view('admin/index');
    }

    public function orders()
    {
        $checkoutModel = new CheckoutModel();
        // Get all checkout records
        $data['checkouts'] = $checkoutModel->findAll();

        return view('admin/orders', $data); // Passing data to the view
    }

    public function inventory()
    {
        $inventoryModel = new ProductModel();
        $data['inventory'] = $inventoryModel->findAll();

        return view('admin/inventory', $data);
    }

    public function addProduct()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'error' => 'Invalid Request Method']);
        }

        $inventoryModel = new ProductModel();
        $image = $this->request->getFile('image');

        // Ensure a default value in case no image is uploaded
        $newName = null;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();  // Generate a unique name
            $image->move(FCPATH . 'uploads', $newName);  // Save the file
        }

        // Ensure that image is not null when inserting into the database
        $data = [
            'image'     => $newName ?? 'default.png',  // Store file name or set a default image
            'name'      => $this->request->getPost('name'),
            'details'   => $this->request->getPost('details'),
            'category'  => $this->request->getPost('category'),
            'stock'     => $this->request->getPost('stock'),
            'price'     => $this->request->getPost('price'),
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ];

        if ($inventoryModel->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Database Insert Failed']);
        }
    }

    public function deleteProduct()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        // ✅ FIX: Use getPost() instead of getJSON()
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'error' => 'Invalid request']);
        }

        $inventoryModel = new ProductModel();
        $product = $inventoryModel->find($id);

        if (!$product) {
            return $this->response->setJSON(['success' => false, 'error' => 'Product not found']);
        }

        if (!empty($product['image']) && file_exists('public/uploads/' . $product['image'])) {
            unlink('public/uploads/' . $product['image']);
        }

        if ($inventoryModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Failed to delete product']);
        }
    }

    public function editProduct()
    {
        if (!$this->request->is('post')) { // ✅ Check if it's a POST request instead of isAJAX()
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Invalid Request Method']);
        }

        $id = $this->request->getPost('id');
        $inventoryModel = new ProductModel();
        $product = $inventoryModel->find($id);

        if (!$product) {
            return $this->response->setJSON(['success' => false, 'error' => 'Product not found']);
        }

        $image = $this->request->getFile('image');
        $newName = $product['image']; // Keep the existing image

        if ($image && $image->isValid() && !$image->hasMoved()) {
            if (!empty($product['image']) && file_exists('public/uploads/' . $product['image'])) {
                unlink('public/uploads/' . $product['image']); // Remove old image
            }
            $newName = $image->getRandomName();
            $image->move('public/uploads', $newName);
        }

        $data = [
            'image'     => $newName,
            'name'      => $this->request->getPost('name'),
            'details'   => $this->request->getPost('details'),
            'category'  => $this->request->getPost('category'),
            'stock'     => $this->request->getPost('stock'),
            'price'     => $this->request->getPost('price'),
            'updated_at'=> date('Y-m-d H:i:s')
        ];

        if ($inventoryModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Update failed']);
        }
    }
}
