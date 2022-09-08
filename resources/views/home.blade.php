@extends('layout')
@section('title') {{ 'Home' }} @endsection
@section('content')
<h1>You are logged in! fsfsdfsdf</h1>
{{ session('current_period').'-'.session('periodname') }}
@endsection
