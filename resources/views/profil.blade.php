@extends('dashboard.index')
@section('title', 'Dashboard')
@section('user')
    <div class="flex-grow-1">
        <h6 class="mb-0">John Doe</h6>
        <small class="text-muted">Admin</small>
    </div>
@endsection
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
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-calendar-clock-outline"></i>
                    <div data-i18n="Authentications">Jadwal</div>
                </a>
                <ul class="menu-sub">
                    @foreach (App\Models\Jadwal::all() as $jadwal)
                        <li class="menu-item">
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Pengaturan Akun</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('profil.username') }}" method="POST">
                @csrf
                <div class="mb-3 w-sm-50">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') ? old('username') : auth()->user()->username}}">
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
            </form>
            <div class="mb-4"></div>
            <form action="{{ route('profil.password') }}" method="POST">
                @csrf
                <div class="mb-3 w-sm-50">
                    <label for="password_sekarang" class="form-label">Password Sekarang</label>
                    <input type="password" class="form-control" id="password_sekarang" name="password_sekarang">
                    @error('password_sekarang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 w-sm-50">
                    <label for="password_baru" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="password_baru" name="password_baru">
                    @error('password_baru')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 w-sm-50">
                    <label for="konfirmasi_password_baru" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="konfirmasi_password_baru" name="konfirmasi_password_baru">
                    @error('konfirmasi_password_baru')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-sm btn-primary" type="submit">Update Password</button>
            </form>
        </div>
    </div>
@endsection