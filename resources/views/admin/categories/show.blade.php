@extends('layouts.admin')
@section('content')
    <h2>Show Category</h2>
    
        <label>Name</label>
        <h3>{{$category->name}}</h3>
        <img src="{{asset('storage/'.$category->image)}}" />
        <a class="btn btn-secondary" href="{{ url('admin/categories') }}">Cancel</a>
    </form>
@endsection
