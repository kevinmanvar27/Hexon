<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'user_type', 'city', 'state', 'state_code', 'address', 'GSTIN', 'business_name', 'bank_name', 'bank_account_no', 'ifsc_code'];
}
