<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CartModel;

class CartController extends ResourceController
{
    protected $modelName = 'App\Models\CartModel';
    protected $format = 'json';

    public function getCarts()
    {
        $cartModel = new CartModel();
        $user_id = $this->request->getVar('user_id'); // Get the user_id from the request

        // Query carts and join with inventory table to get product details
        $builder = $cartModel->builder();
        $builder->select('cart.*, inventory.name as product_name, inventory.details as product_details, inventory.stock as product_stock, inventory.price as product_price, inventory.image as product_image')
                ->join('inventory', 'inventory.id = cart.inventory_id')
                ->where('cart.user_id', $user_id);
        $carts = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'data' => $carts
        ]);
    }

    // ✅ Fetch a Single Cart by ID
    public function getCart($cart_id)
    {
        $cartModel = new CartModel();
        $cart = $cartModel->find($cart_id);

        if (!$cart) {
            return $this->failNotFound('Cart not found');
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $cart
        ]);
    }

    public function createCart()
    {
        // Get data from request (works for both JSON and form data)
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Validate required fields
        $rules = [
            'user_id' => 'required|integer',
            'inventory_id' => 'required|integer',
            'status' => 'required|in_list[Cart,To ship,To receive,Completed]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Insert into database
        $cart_id = $this->model->insert($data);

        // Return success response
        return $this->respondCreated([
            'success' => true,
            'message' => 'Cart added successfully',
            'data' => $this->model->find($cart_id)
        ]);
    }

    // ✅ Edit a Cart
    public function updateCart($cart_id)
    {
        $data = $this->request->getPost();
        $this->model->update($cart_id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    // ✅ Delete a Cart
    public function deleteCart($cart_id)
    {
        $this->model->delete($cart_id);

        return $this->respondDeleted([
            'success' => true,
            'message' => 'Cart deleted successfully'
        ]);
    }
}
