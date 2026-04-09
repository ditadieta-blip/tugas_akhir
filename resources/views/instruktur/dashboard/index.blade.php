@extends('layouts.instruktur')

@section('content')
<h4 class="mb-4 fw-bold">Dashboard Instruktur</h4>

<div class="card p-3">
    <p>Selamat datang, {{ auth()->user()->nama_user }}</p>
</div>
@endsection