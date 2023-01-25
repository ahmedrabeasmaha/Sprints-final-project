<?php

namespace App\Http\Helpers;

class ProductHelper
{
    public static function claculateRating($product, $review)
    {
        $rating = ($product->rating * $product->rating_count) + $review->rating;
        $rating = $rating / ($product->rating_count + 1);
        return $rating;
    }
}
