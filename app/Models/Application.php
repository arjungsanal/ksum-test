<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. IMPORT THIS TRAIT
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory; // <-- 2. USE THIS TRAIT INSIDE THE CLASS

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'short_bio',
        'resume_path',
        'razorpay_order_id',
        'razorpay_payment_id',
        'status',
    ];
}
