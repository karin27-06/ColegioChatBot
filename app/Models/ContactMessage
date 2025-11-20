<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'email', 
        'phone',
        'subject',
        'whatsapp_message',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
