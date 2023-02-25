<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['messageRecu_id', 'messageEnvoye_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class, 'messageEnvoye_id', 'unique_id');
    }
}
