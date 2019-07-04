@extends('layouts.app')

@section('title', $topic->title)

@section('content')
  <div>{{ $topic->body }}</div>
@stop
