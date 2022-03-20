<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SmsProvider;

class Product extends Model
{

    // public function messages() {
    //     return $this->hasMany(Message::class);
    // }

    public function provider() {
        return $this->belongsTo(SmsProvider::class);
    }

    use HasFactory;
}
