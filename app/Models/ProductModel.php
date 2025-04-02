<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $allowedFields = ['image', 'name', 'details', 'category', 'stock', 'price', 'created_at', 'updated_at'];
}

