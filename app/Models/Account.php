<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $primaryKey = 'id'; // Assuming 'Account_id' is the primary key
    public $timestamps = false;
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'phone',
        'password',
        'role',
    ];

    // You can define any relationships or additional methods here
}