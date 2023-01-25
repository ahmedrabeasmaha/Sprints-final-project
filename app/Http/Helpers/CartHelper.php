<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class CartHelper
{
    public static function calcShipping($products)
    {
        $shipping = 0;
        foreach ($products as $product) {
            $shipping += 1 * $product[1];
        }
        return $shipping;
    }
    public static function calcSubTotal($products)
    {
        $subTotal = 0;
        foreach ($products as $product) {
            $subTotal += ($product[0]['price'] - ($product[0]['price'] * $product[0]['discount'])) * $product[1];
        }
        return $subTotal;
    }
    public static function calcTotal($shipping, $subTotal)
    {
        return $shipping + $subTotal;
    }
}
