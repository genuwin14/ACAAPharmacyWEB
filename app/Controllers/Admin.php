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

    public function customer()
    {
        $userModel = new \App\Models\UserModel();
        $users = $userModel->findAll(); // fetch all users

        return view('admin/customer', ['users' => $users]);
    }

    public function orders()
    {
        $checkoutModel = new \App\Models\CheckoutModel();
        $cartModel     = new \App\Models\CartModel();
        $productModel  = new \App\Models\ProductModel();
        $userModel     = new \App\Models\UserModel();

        $checkouts = $checkoutModel->where('status', 'Pending')->findAll();

        $groupedCheckouts = [];

        foreach ($checkouts as $checkout) {
            $userId = $checkout['user_id'];

            $cart = $cartModel->find($checkout['cart_id']);
            $product = $productModel->find($cart['inventory_id']);
            $user = $userModel->find($userId);

            // Add checkout_id to $item
            $item = [
                'checkout_id'    => $checkout['checkout_id'],  // Ensure checkout_id is included
                'product_name'   => $product['name'] ?? 'Unknown',
                'product_image'  => $product['image'] ?? 'default.jpg',
                'product_price'  => $product['price'] ?? 0,
                'quantity'       => $cart['quantity'] ?? 1,
                'pickup_date'    => $checkout['pickup_date'],
                'datetime_received' => $checkout['datetime_received'],
                'total_amount'   => $checkout['total_amount'],
            ];

            if (!isset($groupedCheckouts[$userId])) {
                $groupedCheckouts[$userId] = [
                    'user_name' => $user['name'] ?? 'Unknown',
                    'items'     => [],
                    'amounts'   => [],
                ];
            }

            // Avoid duplicate cart_id/product entry
            $groupedCheckouts[$userId]['items'][] = $item;
            $groupedCheckouts[$userId]['amounts'][] = $checkout['total_amount'];
        }

        // Final processing to clean up repeated total_amount
        foreach ($groupedCheckouts as &$group) {
            $uniqueAmounts = array_unique($group['amounts']);
            $group['final_amount'] = count($uniqueAmounts) === 1
                ? $uniqueAmounts[0]
                : array_sum($group['amounts']);
        }

        $data['groupedCheckouts'] = $groupedCheckouts;

        return view('admin/orders', $data);
    }

    public function setPickupDate()
    {
        $checkoutModel = new \App\Models\CheckoutModel();
        
        // Get form inputs
        $pickupDate = $this->request->getPost('pickup_date');
        $checkoutIds = $this->request->getPost('checkout_ids'); // Array of checkout_ids
        
        // Validate the input (check if the pickup_date is provided)
        if ($pickupDate && !empty($checkoutIds)) {
            // Update the checkout records with the selected pickup date
            foreach ($checkoutIds as $checkoutId) {
                $checkoutModel->update($checkoutId, [
                    'pickup_date' => $pickupDate
                ]);
            }
            
            // Redirect back to the orders page (or wherever appropriate)
            return redirect()->to('/orders')->with('message', 'Pickup date updated successfully');
        }
        
        // If no pickup date was provided, return an error
        return redirect()->to('/orders')->with('error', 'Failed to update pickup date');
    }

    public function receiveProduct()
    {
        $checkoutModel = new \App\Models\CheckoutModel();

        // Get the current datetime for received products
        $datetimeReceived = date('Y-m-d H:i:s');

        // Get the list of checkout_ids (array of selected products)
        $checkoutIds = $this->request->getPost('checkout_ids');

        if (!empty($checkoutIds)) {
            foreach ($checkoutIds as $checkoutId) {
                // Update the datetime_received and status fields for each selected product
                $checkoutModel->update($checkoutId, [
                    'datetime_received' => $datetimeReceived,
                    'status'            => 'Received'  // Update the status to 'Received'
                ]);
            }

            // Redirect back to the orders page with a success message
            return redirect()->to('/orders')->with('message', 'Product(s) marked as received successfully');
        }

        // If no checkout IDs are provided, redirect back with an error
        return redirect()->to('/orders')->with('error', 'Failed to mark product(s) as received');
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
