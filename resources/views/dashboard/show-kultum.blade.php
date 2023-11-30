@extends('dashboard.detail-index')
@section('title', 'kultum')

@section('content')
    <div class="card p-4">
        <div class="card-body">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary">
                <i class="mdi mdi-home-circle-outline me-2"></i>
                Beranda
            </a>
            <h5 class="fw-bold my-4">{{ $kultum->judul }}</h5>
            {!! html_entity_decode($kultum->isi) !!}
        </div>
    </div>
@endsection