@extends('layouts.admin')
@section('title', 'Nuevo Itinerario')
@section('header', 'Nuevo Itinerario')

@section('content')
@include('admin.itinerarios._form', ['itinerario' => null, 'action' => route('admin.itinerarios.store'), 'method' => 'POST'])
@endsection
