@extends('layouts.admin')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>total</th>
                <th colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach (as $order)
                <tr>
                    <td>{{ $order['id'] }}</td>
                    <td>{{ $order['total'] }}</td>
                    <td><a href="{{ route('orders.show', $order['id']) }}">Show</a></td>
                </tr>
            @endforeach

        </tbody>
    </table>
    {!! $orders->links() !!}
@endsection
