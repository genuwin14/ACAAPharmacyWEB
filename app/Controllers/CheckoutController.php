<?php

namespace App\Controllers;

use App\Models\CheckoutModel;
use CodeIgniter\RESTful\ResourceController;

class CheckoutController extends ResourceController
{
    protected $modelName = 'App\Models\CheckoutModel';
    protected $format = 'json';

    // Fetch all checkouts
    public function getCheckouts()
    {
        // Fetch all checkouts
        $checkouts = $this->model->findAll();

        // Check if there are any checkouts
        if ($checkouts) {
            // Format checkouts in a way the frontend expects, focusing on cart_id
            $checkoutData = array_map(function($checkout) {
                return [
                    'cart_id' => json_decode($checkout['cart_id']), // Assuming cart_id is a JSON string in the database
                    'user_id' => $checkout['user_id'],
                    'status' => $checkout['status'],
                    'pickup_date' => $checkout['pickup_date'],
                    'datetime_received' => $checkout['datetime_received'],
                    'total_amount' => $checkout['total_amount'],
                ];
            }, $checkouts);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Checkouts retrieved successfully',
                'data' => $checkoutData
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No checkouts found',
                'data' => []
            ]);
        }
    }

    // Fetch single checkout by ID
    public function getCheckout($id = null)
    {
        $checkout = $this->model->find($id);

        if ($checkout) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Checkout retrieved successfully',
                'data' => $checkout
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Checkout not found',
                'data' => null
            ])->setStatusCode(404);
        }
    }

    public function createCheckout()
    {
        $data = $this->request->getJSON(true);

        // 🔒 Manually check if cart_id is an array of integers
        if (!isset($data['cart_id']) || !is_array($data['cart_id']) || empty($data['cart_id'])) {
            return $this->failValidationErrors(['cart_id' => 'The cart_id field must be a non-empty array.']);
        }

        foreach ($data['cart_id'] as $id) {
            if (!is_numeric($id)) {
                return $this->failValidationErrors(['cart_id' => 'Each cart_id must be an integer.']);
            }
        }

        // ✅ Now validate the rest
        if (!$this->validate([
            'user_id' => 'required|integer',
            'status' => 'required|in_list[Pending,Received]',
            'pickup_date' => 'permit_empty|valid_date',
            'datetime_received' => 'permit_empty|valid_date',
            'total_amount' => 'required|decimal',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Prepare batch insert
        $checkoutData = [];
        foreach ($data['cart_id'] as $cartId) {
            $checkoutData[] = [
                'cart_id' => $cartId,
                'user_id' => $data['user_id'],
                'status' => $data['status'],
                'pickup_date' => $data['pickup_date'] ?? null,
                'datetime_received' => $data['datetime_received'] ?? null,
                'total_amount' => $data['total_amount'],
            ];
        }

        // Insert batch
        if ($this->model->insertBatch($checkoutData)) {
            return $this->respondCreated(['message' => 'Checkout(s) created successfully.']);
        } else {
            return $this->failServerError('Failed to create checkout');
        }
    }

    // Update checkout information
    public function updateCheckout($id = null)
    {
        $data = $this->request->getRawInput();
        
        // Validate input (optional)
        if (!$this->validate([
            'status' => 'required|in_list[Pending,Received]',
            'pickup_date' => 'permit_empty|valid_date',
            'datetime_received' => 'permit_empty|valid_date',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $checkoutData = [
            'status' => $data['status'],
            'pickup_date' => isset($data['pickup_date']) ? $data['pickup_date'] : null,
            'datetime_received' => isset($data['datetime_received']) ? $data['datetime_received'] : null,
        ];

        // Update the checkout entry
        if ($this->model->update($id, $checkoutData)) {
            return $this->respondUpdated(['checkout_id' => $id]);
        } else {
            return $this->failNotFound('Checkout not found or no changes made');
        }
    }

    // Delete checkout by ID
    public function deleteCheckout($id = null)
    {
        // Check if the checkout exists
        if ($this->model->find($id)) {
            // Delete the checkout
            $this->model->delete($id);
            return $this->respondDeleted(['checkout_id' => $id]);
        } else {
            return $this->failNotFound('Checkout not found');
        }
    }
}
