@extends('layouts.admin')
@section('content')
    <h2>Show Order</h2>
        <label>Name</label>
        <h3>{{$order->id}}</h3>
        <h3>{{$order->address}}</h3>
        <h3>{{$order->countru}}</h3>
        <h3>{{$order->city}}</h3>
        <h3>{{$order->total}}</h3>
        <h3>{{$order->shipping}}</h3>
        @foreach ($order->oredrDetails as $order_detail)
        <h3>{{$order_detail->product->name}}</h3>
        <h3>{{$order_detail->price}}</h3>
        <h3>{{$order_detail->quantity}}</h3>
        @endforeach
    </form>
@endsection
