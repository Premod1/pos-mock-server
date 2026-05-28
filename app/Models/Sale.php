<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'amount', 'customer_mobile', 'status', 'pos_response'];
}
