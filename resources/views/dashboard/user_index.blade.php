@extends('dashboard.user')
@section('title', 'dashboard')

@section('content')
    <div class="tab-content p-0">
        @foreach (App\Models\Jadwal::all() as $jadwal)
            <div class="tab-pane fade {{ $loop->iteration == 1 ? 'active show' : '' }}" id="navs-tab-{{ $jadwal->type_jadwal }}"
                role="tabpanel">
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">

                    <h1>asdjoiasdoia</h1>

                </div>
            </div>
        @endforeach
    </div>
@endsection
