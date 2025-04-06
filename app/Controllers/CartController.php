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

        // Log the user_id to ensure it's being passed correctly
        log_message('debug', 'User ID: ' . $user_id);

        if (empty($user_id)) {
            return $this->fail('User ID is required.', 400);
        }

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

        // Check if the product already exists in the cart with the same status "Cart"
        $existingCartItem = $cartModel
            ->where('user_id', $userId)
            ->where('inventory_id', $inventoryId)
            ->where('status', 'Cart')  // Ensure status is "Cart"
            ->first();

        if ($existingCartItem) {
            // If the item exists, update the quantity
            $updatedQuantity = (int)$existingCartItem['quantity'] + 1;
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
            // If the product is not in the cart, insert a new cart item
            $cartModel->insert([
                'user_id' => $userId,
                'inventory_id' => $inventoryId,
                'quantity' => 1, // Default quantity is 1 when adding to the cart
                'status' => 'Cart', // Always set status to "Cart"
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->respondCreated([
                'success' => true,
                'message' => 'Product added to cart successfully.'
            ]);
        }
    }

    public function updateCart($cart_id)
    {
        // Create an instance of the CartModel
        $cartModel = new CartModel();

        // Fetch the cart item by its cart_id
        $cart = $cartModel->find($cart_id);

        // Check if the cart item exists
        if (!$cart) {
            return $this->failNotFound("Cart ID $cart_id not found.");
        }

        // Get the data from the request (JSON body)
        $data = $this->request->getJSON(true);  // true = associative array

        // Log the received data for debugging purposes
        log_message('debug', 'Received data: ' . print_r($data, true));

        // Check if quantity is present in the data
        if (empty($data['quantity'])) {
            return $this->fail('Quantity is required.', 400);
        }

        // Update the cart with the received data
        $cartModel->update($cart_id, $data);

        return $this->respond([
            'success' => true,
            'message' => 'Cart updated successfully'
        ]);
    }

    public function updateCartStatus()
    {
        $cartModel = new CartModel();

        // Get the input data (cart_ids and status)
        $json = $this->request->getJSON(true); // true = return as associative array
        $cartIds = $json['cart_ids'] ?? null;
        $status = $json['status'] ?? null;

        // Validate input
        if (empty($cartIds) || empty($status)) {
            return $this->fail('cart_ids and status are required.', 400);
        }

        // Ensure the status is "To receive"
        if ($status !== 'To receive') {
            return $this->fail('Invalid status. Only "To receive" is allowed.', 400);
        }

        // Update the status of the selected cart items
        $builder = $cartModel->builder();
        $builder->whereIn('cart_id', $cartIds);
        $builder->update(['status' => 'To receive', 'updated_at' => date('Y-m-d H:i:s')]);

        return $this->respond([
            'success' => true,
            'message' => 'Cart items status updated to "To receive".'
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
