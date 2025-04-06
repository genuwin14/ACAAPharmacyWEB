<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model
{
    protected $table = 'checkout';
    protected $primaryKey = 'checkout_id';
    protected $allowedFields = ['cart_id', 'user_id', 'status', 'pickup_date', 'datetime_received', 'created_at', 'updated_at', 'total_amount']; // Added total_amount
    protected $useTimestamps = true;

    // Define relationships
    public function cart()
    {
        return $this->belongsTo('App\Models\CartModel', 'cart_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }
}
