<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SmsProvider;
use App\Models\Product;

class Message extends Model
{
    use HasFactory;


    public function provider() {
        return $this->belongsTo(SmsProvider::class);
    }
    
    public function product() {
        return $this->belongsTo(Product::class);
    }



}
