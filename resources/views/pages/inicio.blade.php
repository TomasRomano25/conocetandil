@extends('layouts.app')

@section('title', 'Conoce Tandil - Inicio')

@section('content')
    @foreach($sections as $section)
        @includeIf('pages.sections.' . $section->key)
    @endforeach
@endsection
