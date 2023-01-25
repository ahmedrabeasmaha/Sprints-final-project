<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $hidden = ['user_id', 'product_id', 'created_at', 'updated_at'];
    public static $rules = [
        'review' => 'required',
        'rating' => 'required'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(product::class);
    }
}
