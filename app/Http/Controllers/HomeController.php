<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CartHelper;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Review;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\ProductHelper;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    function index()
    {

        return view('index')->with([
            'categories' => Category::all(),
            'products' => Product::all()
        ]);
    }

    function shop(Request $request)
    {
        $query = Product::query();

        $inputs = $request->all();

        if (isset($inputs['keywords'])) {
            $query = $query->where('name', 'like', "%" . $inputs['keywords'] . "%");
        }
        if (isset($inputs['color'])) {
            if (!in_array('-1', $inputs['color'])) {

                $query = $query->whereIn('color_id', $inputs['color']);
            }
        }
        if (isset($inputs['size'])) {
            if (!in_array('-1', $inputs['size'])) {
                $query = $query->whereIn('size_id', $inputs['size']);
            }
        }

        if ($request->has('category_id')) {
            $query = $query->where('category_id', $request->get('category_id'));
        }

        if ($request->has('price')) {
            if (!in_array('-1', $inputs['price'])) {
                $query = $query->where(function ($q) use ($inputs) {
                    foreach ($inputs['price'] as $price) {
                        $q = $q->orWhereBetween('price', [$price, $price + 100]);
                    }
                });
            }
        }

        /*SELECT * FROM Products WHERE con1 and con2 and (
        price between 0 and 100 or
        price between 100 and 200
        )
        */

        $products = $query->paginate(9);


        return view('shop')->with([
            'products' => $products,
            'colors' => Color::all(),
            'sizes' => Size::all()
        ]);
    }
    function item($id)
    {
        $query = Product::findOrFail($id);
        $reviews = Review::with(['product', 'user'])->get();
        return view('item')->with(['product' => $query, 'reviews' => $reviews]);
    }
    function review(Request $request, $id)
    {
        $request->validate(Review::$rules);
        $review = new Review;
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = auth()->user()->id;
        $review->product_id = $id;
        $review->save();
        $product = Product::findOrFail($id);
        $product->rating = ProductHelper::claculateRating($product, $review);
        $product->rating_count += 1;
        $product->save();
        return redirect()->route('item', $id);
    }

    function add_product(Request $request)
    {
        if ($request->has('id')) {
            $ids = Session::get('ids', []);
            if ($request->has('quantity')) {
                if (array_key_exists($request->get('id'), $ids)) {
                    $ids[$request->get('id')] += $request->get('quantity');
                } else {
                    $ids[$request->get('id')] = $request->get('quantity');
                }
            } else {
                if (array_key_exists($request->get('id'), $ids)) {
                    $ids[$request->get('id')] += 1;
                } else {
                    $ids[$request->get('id')] = 1;
                }
            }
            Session::put('ids', $ids);

            return response()->json(array_sum($ids));
        }
        return abort(404);
    }
    function add_loved(Request $request)
    {
        if ($request->has('id')) {
            $ids = Session::get('like', []);
            if (in_array($request->get('id'), $ids)) {
                $rem = array_search($request->get('id'), array_values($ids));
                array_splice($ids, $rem, 1);
                Session::put('like', $ids);
                return response()->json([0 => false, 1 => $ids]);
            } else {
                array_push($ids, $request->get('id'));
                Session::put('like', $ids);
                return response()->json([0 => true, 1 => $ids]);
            }
        }
        return abort(404);
    }
    function cart(Request $request)
    {
        $ids = Session::get('ids', []);
        $products = [];
        foreach ($ids as $id => $quantity) {
            if (Product::find($id)) {
                $product = Product::find($id);
                array_push($products, [$product, $quantity]);
            }
        }
        $shipping = CartHelper::calcShipping($products);
        $subTotal = CartHelper::calcSubTotal($products);
        $total = CartHelper::calcTotal($shipping, $subTotal);
        return view('cart', ['products' => $products, 'shipping' => $shipping, 'subTotal' => $subTotal, 'total' => $total]);
    }
    function inc_quantity(Request $request)
    {
        if ($request->get('id')) {
            $ids = Session::get('ids', []);
            if ($ids[$request->get('id')]) {
                $ids[$request->get('id')] += 1;
            } else {
                $ids[$request->get('id')] = 1;
            }
            $products = [];
            foreach ($ids as $id => $quantity) {
                if (Product::find($id)) {
                    $product = Product::find($id);
                    array_push($products, [$product, $quantity]);
                }
            }
            $shipping = CartHelper::calcShipping($products);
            $subTotal = CartHelper::calcSubTotal($products);
            $total = CartHelper::calcTotal($shipping, $subTotal);
            Session::put('ids', $ids);
            return response()->json(['ids' => $ids, 'shipping' => $shipping, 'subTotal' => $subTotal, 'total' => $total]);
        }
        return abort(404);
    }
    function dec_quantity(Request $request)
    {
        if ($request->get('id')) {
            $ids = Session::get('ids', []);
            if ($ids[$request->has('id')] && $ids[$request->get('id')] > 1) {
                $ids[$request->get('id')] -= 1;
            }
            $products = [];
            foreach ($ids as $id => $quantity) {
                if (Product::find($id)) {
                    $product = Product::find($id);
                    array_push($products, [$product, $quantity]);
                }
            }
            $shipping = CartHelper::calcShipping($products);
            $subTotal = CartHelper::calcSubTotal($products);
            $total = CartHelper::calcTotal($shipping, $subTotal);
            Session::put('ids', $ids);
            return response()->json(['ids' => $ids, 'shipping' => $shipping, 'subTotal' => $subTotal, 'total' => $total]);
        }
        return abort(404);
    }
    function del_product(Request $request)
    {
        if ($request->has('id')) {
            $ids = Session::get('ids', []);
            if ($ids[$request->get('id')]) {
                unset($ids[$request->get('id')]);
            }
            $products = [];
            foreach ($ids as $id => $quantity) {
                if (Product::find($id)) {
                    $product = Product::find($id);
                    array_push($products, [$product, $quantity]);
                }
            }
            $shipping = CartHelper::calcShipping($products);
            $subTotal = CartHelper::calcSubTotal($products);
            $total = CartHelper::calcTotal($shipping, $subTotal);
            Session::put('ids', $ids);
            return response()->json(['ids' => $ids, 'shipping' => $shipping, 'subTotal' => $subTotal, 'total' => $total]);
        }
        return abort(404);
    }
    function checkout()
    {
        $ids = Session::get('ids', []);
        $products = [];
        foreach ($ids as $id => $quantity) {
            if (Product::find($id)) {
                $product = Product::find($id);
                array_push($products, [$product, $quantity]);
            }
        }
        $shipping = CartHelper::calcShipping($products);
        $subTotal = CartHelper::calcSubTotal($products);
        $total = CartHelper::calcTotal($shipping, $subTotal);
        Session::put('ids', $ids);
        return view('checkout')->with(['products' => $products, 'shipping' => $shipping, 'subTotal' => $subTotal, 'total' => $total]);
    }
    function checkoutpost(Request $request)
    {
        $request->validate(Order::$rules);
        $ids = Session::get('ids', []);
        $products = [];
        foreach ($ids as $id => $quantity) {
            if (Product::find($id)) {
                $product = Product::find($id);
                array_push($products, [$product, $quantity]);
            }
        }
        $shipping = CartHelper::calcShipping($products);
        $subTotal = CartHelper::calcSubTotal($products);
        $total = CartHelper::calcTotal($shipping, $subTotal);
        $order = new Order;
        $order->fill($request->post());
        $order->total = $total;
        $order->shipping = $shipping;
        $order->user_id = Auth::id();
        $order->created_at = now();
        $order->updated_at = now();
        $order->save();

        foreach ($products as $product) {
            $order_details = new OrderDetails;
            $order_details->order_id = $order->id;
            $order_details->price = $product[0]->price;
            $order_details->quantity = $product[1];
            $order_details->product_id = $product[0]->id;
            $order_details->save();
        }
        Session::put('ids', []);
        return redirect('/');
    }
}
