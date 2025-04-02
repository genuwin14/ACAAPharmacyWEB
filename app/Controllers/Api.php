<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    protected $format = 'json';

    public function getUsers()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM users");
        $users = $query->getResult();

        return $this->respond($users);
    }
}
