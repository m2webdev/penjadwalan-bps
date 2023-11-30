@extends('dashboard.user')
@section('title', 'dashboard')

@section('content')
    <div class="card p-4">
        <div class="card-body">
            <h5 class="fw-bold mb-4">{{ $kultum->judul }}</h5>
            {!! html_entity_decode($kultum->isi) !!}
        </div>
    </div>
@endsection