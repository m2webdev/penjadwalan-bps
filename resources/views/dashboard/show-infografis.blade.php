@extends('dashboard.detail-index')
@section('title', 'infografis')

@section('content')
    <div class="card p-4">
        <div class="card-body d-flex flex-column align-items-start">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary d-block">
                <i class="mdi mdi-home-circle-outline me-2"></i>
                Beranda
            </a>
            <img src="{{ asset('storage/infografis/' . ($infografis->gambar ? $infografis->gambar : 'bps.png')) }}" alt="{{ $infografis->judul }}" class="form-img show mt-4 mx-auto">
            <h5 class="fw-bold my-4">{{ $infografis->judul }}</h5>
            {!! html_entity_decode($infografis->isi) !!}
            <div class="mt-5">
                @php($penjadwalan = App\Models\Penjadwalan::where('infografis_id', $infografis->id)->first())
                <h6><span style="color: #EA8B14">Infografis</span><span class="mx-3">|</span><span style="color: #6FB729; text-transform: capitalize;">{{ $penjadwalan ? $penjadwalan->user->name : '' }}</span></h6>
            </div>
        </div>
    </div>
@endsection