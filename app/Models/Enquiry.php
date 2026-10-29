<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'subject',
        'enquiry_type',
        'status',
        'preferred_contact_time',
    ];

    protected $casts = [
        'preferred_contact_time' => 'datetime',
    ];
}