@extends('layouts.admin')
@section('title', 'Editar Itinerario')
@section('header', 'Editar Itinerario')

@section('content')
@include('admin.itinerarios._form', ['action' => route('admin.itinerarios.update', $itinerario), 'method' => 'PUT'])
@endsection
