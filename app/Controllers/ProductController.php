<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;

class ProductController extends ResourceController
{
    protected $modelName = 'App\Models\ProductModel';
    protected $format = 'json';

    // public function testApi()
    // {
    //     return $this->respond(['success' => true, 'message' => 'API is working']);
    // }

    // ✅ Fetch All Products
    public function getProducts()
    {
        $productModel = new ProductModel();
        $products = $productModel->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $products
        ]);
    }

    // ✅ Add a Product
    public function addProduct()
    {
        $data = $this->request->getPost();
        $this->model->insert($data);
        return $this->respondCreated(['success' => true, 'message' => 'Product added successfully']);
    }

    // ✅ Edit a Product
    public function editProduct()
    {
        $id = $this->request->getPost('id');
        $data = $this->request->getPost();
        unset($data['id']); // Don't update the ID
        $this->model->update($id, $data);
        return $this->respond(['success' => true, 'message' => 'Product updated successfully']);
    }

    // ✅ Delete a Product
    public function deleteProduct()
    {
        $id = $this->request->getPost('id');
        $this->model->delete($id);
        return $this->respondDeleted(['success' => true, 'message' => 'Product deleted successfully']);
    }
}
