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
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        
        $cartModel = new CartModel();

        // Validate required fields
        $rules = [
            'user_id' => 'required|integer',
            'inventory_id' => 'required|integer',
            'quantity' => 'required|integer|min_length[1]',
            'status' => 'required|in_list[Cart,To ship,To receive,Completed]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Check if product already exists in cart for the user
        $existingCart = $cartModel->where([
            'user_id' => $data['user_id'],
            'inventory_id' => $data['inventory_id']
        ])->first();

        if ($existingCart) {
            // If product exists, calculate the updated quantity
            $updatedQuantity = $data['quantity'] + 1; // Increment by 1 for simplicity

            return $this->respond([
                'success' => true,
                'message' => 'Cart updated successfully',
                'data' => [
                    'id' => $existingCart['id'], // Ensure 'id' is included
                    'user_id' => $existingCart['user_id'],
                    'inventory_id' => $existingCart['inventory_id'],
                    'quantity' => $updatedQuantity, // Pass the updated quantity
                    'status' => $existingCart['status']
                ]
            ]);
        } else {
            // If product does not exist, insert a new row
            $cart_id = $cartModel->insert($data);

            return $this->respondCreated([
                'success' => true,
                'message' => 'Cart added successfully',
                'data' => [
                    'id' => $cart_id, // Ensure 'id' is included
                    'user_id' => $data['user_id'],
                    'inventory_id' => $data['inventory_id'],
                    'quantity' => $data['quantity'], // Pass the initial quantity
                    'status' => $data['status']
                ]
            ]);
        }
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
