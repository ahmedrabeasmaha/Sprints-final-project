<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'mobile_num',
        'address',
        'countru',
        'city'
    ];
    public static $rules = [
        'mobile_num' => 'required',
        'address' => 'required',
        'countru' => 'required',
        'city' => 'required'
    ];
    public function oredrDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
