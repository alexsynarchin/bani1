@extends('admin.base.base')
@section('content')
    <h1 class="page-title">Список броней</h1>
    <places-dashboard :user = "{{Auth::user()}}"></places-dashboard>
@endsection
