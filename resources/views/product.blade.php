@extends('layouts.main')

@section('title', 'HProduto')

@section('content')


    @if($id != null)
    <p>Exibindo produto id: {{ $id }}</p>
    @endif


@endsection
