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
        $cartModel = new CartModel();

        // ✅ Correct way to handle JSON input
        $json = $this->request->getJSON(true); // true = return as associative array
        $userId = $json['user_id'] ?? null;
        $inventoryId = $json['inventory_id'] ?? null;

        if (!$userId || !$inventoryId) {
            return $this->fail('User ID and Inventory ID are required.', 400);
        }

        // Check if product is already in cart
        $existingCartItem = $cartModel
            ->where('user_id', $userId)
            ->where('inventory_id', $inventoryId)
            ->first();

        if ($existingCartItem) {
            $updatedQuantity = $existingCartItem['quantity'] + 1;
            $cartModel->update($existingCartItem['cart_id'], [
                'quantity' => $updatedQuantity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respond([
                'success' => true,
                'message' => 'Product quantity updated in cart.',
                'quantity' => $updatedQuantity
            ]);
        } else {
            $cartModel->insert([
                'user_id' => $userId,
                'inventory_id' => $inventoryId,
                'quantity' => 1,
                'status' => 'Cart',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated([
                'success' => true,
                'message' => 'Product added to cart successfully.'
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
        $cartModel = new CartModel();
        $cart = $cartModel->find($cart_id);

        if (!$cart) {
            return $this->failNotFound("Cart ID $cart_id not found.");
        }

        if ($cartModel->delete($cart_id)) {
            return $this->respondDeleted([
                'success' => true,
                'message' => "Cart ID $cart_id deleted successfully."
            ]);
        } else {
            return $this->fail("Failed to delete cart item.");
        }
    }

}
