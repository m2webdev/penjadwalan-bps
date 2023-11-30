@extends('dashboard.index')
@section('title', 'Dashboard')

@section('aside')
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
            <a href="" class="app-brand-link">
                <span class="app-brand-text demo menu-text fw-semibold ">SI - Jati</span>
            </a>

            <a href="{{ route('dashboard') }}" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Dashboards">Dashboard</div>
                </a>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Settings</span>
            </li>
            <!-- Apps -->
            <!-- Pages -->
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                    <div data-i18n="Account Settings">Pengaturan Akun</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('akun.index') }}" class="menu-link">
                            <div data-i18n="Account">Pengguna</div>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="menu-item ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-application-outline"></i>
                    <div data-i18n="Authentications">Pengaturan Jadwal</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('jadwal.index') }}" class="menu-link">
                            <div data-i18n="Account">Jadwal</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-calendar-clock-outline"></i>
                    <div data-i18n="Authentications">Jadwal</div>
                </a>
                <ul class="menu-sub">
                    @foreach (App\Models\Jadwal::all() as $jadwal)
                        <li class="menu-item {{ $penjadwalan->jadwal_id == $jadwal->id ? 'active' : '' }}">
                            <a href="{{ route('penjadwalan.jadwal', ['id' => $jadwal->id]) }}" class="menu-link">
                                <div data-i18n="Basic">{{ $jadwal->type_jadwal }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="menu-item">
                <a href="{{ route('laporan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                    <div data-i18n="Authentications">Laporan</div>
                </a>
            </li>
    </aside>
@endsection

@section('content')
    <div class="card p-4">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Infografis</h5>
            </div>
        </div>
        <div class="card-body">
            @if (session('infografis'))
                <div class="alert alert-success alert-dismissible mb-3 w-100" role="alert">
                    {{ session('infografis') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="" method="POST">
                @csrf
                <input type="text" value="{{ $penjadwalan->infografis_id }}" name="infografis_id" hidden>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" id="judul" value="{{ $penjadwalan->infografis ? $penjadwalan->infografis->judul : '' }}">
                    @error('judul')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="isiinfografis" class="form-label">Isi infografis</label>
                    <textarea rows="5" name="isi" class="form-control" id="isiinfografis">
                        {!! $penjadwalan->infografis ? html_entity_decode($penjadwalan->infografis->isi) : '' !!}
                    </textarea>
                    @error('isi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
            @if($penjadwalan->infografis)
                <form action="{{ route('save.img.infografis', ['id' => $penjadwalan->infografis_id]) }}" method="POST" class="d-flex flex-column gap-3 mt-5 align-items-start" enctype="multipart/form-data">
                    @csrf
                    <h6 class="fw-bold">Gambar Infografis</h6>
                    <img id="previewImage" src="{{ asset('storage/infografis/' . ($penjadwalan->infografis->gambar ? $penjadwalan->infografis->gambar : 'bps.png')) }}" alt="{{ $penjadwalan->infografis ? $penjadwalan->infografis->judul : 'infografis-bps-bonebol' }}" class="form-img" onclick="document.getElementById('infografis-img').click()">
                    <input type="file" id="infografis-img" name="gambar" hidden>
                    @error('gambar')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <button class="btn btn-primary" type="submit">Upload</button>
                </form>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(window).on('load', function() {
            $('#isiinfografis').summernote({
                placeholder: 'isi infografis...',
                tabsize: 2,
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]
            })
        })
        document.getElementById('infografis-img').addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewImage = document.getElementById('previewImage');
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
@endpush