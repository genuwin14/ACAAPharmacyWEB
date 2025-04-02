<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    // Get all users
    public function getUsers()
    {
        return $this->respond($this->model->findAll());
    }

    // Add a new user (No password hashing)
    public function addUser()
    {
        try {
            $data = $this->request->getJSON(true); // Decode JSON body

            // Validate required fields
            if (!isset($data['name'], $data['username'], $data['password'])) {
                return $this->failValidationErrors('Missing required fields');
            }

            // Insert into the database
            if ($this->model->insert($data)) {
                return $this->respondCreated(['message' => 'User created successfully']);
            }

            return $this->fail('Failed to create user');
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    // Edit a user (No password hashing)
    public function editUser()
    {
        $data = $this->request->getRawInput();
        $id   = $data['id'] ?? null;

        if (!$id) {
            return $this->fail('User ID is required');
        }

        if ($this->model->update($id, $data)) {
            return $this->respond(['message' => 'User updated successfully']);
        }

        return $this->fail('Failed to update user');
    }

    // Delete a user
    public function deleteUser()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->fail('User ID is required');
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['message' => 'User deleted successfully']);
        }

        return $this->fail('Failed to delete user');
    }
}
