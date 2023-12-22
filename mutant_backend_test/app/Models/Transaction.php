<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'payment_intent_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}