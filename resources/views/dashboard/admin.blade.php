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
            <li class="menu-item active">
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
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Laporan</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial rounded shadow">
                                <i class="mdi mdi-trending-up mdi-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Jumlah Penjadwalan</div>
                            <h5 class="mb-0">{{ $laporan['jumlah_penjadwalan'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial bg-success rounded shadow">
                                <i class="mdi mdi-account-outline mdi-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Jumlah Pengguna</div>
                            <h5 class="mb-0">{{ $laporan['jumlah_pengguna'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                        <div class="avatar">
                            <div class="avatar-initial rounded shadow">
                                <i class="mdi mdi-application-outline mdi-24px"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="small mb-1">Jumlah Jadwal</div>
                            <h5 class="mb-0">{{ $laporan['jumlah_jadwal'] }}</h5>
                        </div>
                    </div>
                </div>

            </div>
            @if (count($laporan['allPenjadwalan']) > 0)
                <div class="d-flex align-items-center justify-content-between mt-5 mb-4">
                    <h6 class="fw-bold m-0">Jadwal Hari Ini</h6>
                    <a href="{{ route('send.notification.alert.manually') }}" class="btn btn-primary">Kirim Notifikasi Telegram Secara Manual</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped border border-2" style="--bs-table-striped-bg: #55A5CC; --bs-table-striped-color: white; --bs-table-border-color: #55A5CC;">
                        <thead class="table-head">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Jadwal</th>
                                <th scope="col">Pelaksana</th>
                                <th scope="col">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan['allPenjadwalan'] as $penjadwalan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $penjadwalan->jadwal->type_jadwal }}</td>
                                    <td>{{ $penjadwalan->user->name }}</td>
                                    <td>{{ $penjadwalan->tanggal_jadwal ? Carbon\Carbon::parse($penjadwalan->tanggal_jadwal)->translatedFormat('l, d F Y') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="mt-5">
                <h6 class="fw-semibold">Buat Pesan Uji Coba Untuk Notifikasi Telegram</h6>
                <p>Cara mendapatkan telegram id:</p>
                <ul>
                    <li>Tulis di kolom pencarian telegram <a href="https://t.me/userinfobot" class="text-decoration-none"><strong>@userinfobot</strong></a></li>
                    <li>Tap bot pada hasil pencarian</li>
                    <li>Klik start</li>
                </ul>
                <p>Terhubung dengan bot SIJATI:</p>
                <ul>
                    <li>Tulis di kolom pencarian telegram <a href="https://t.me/sijati_bps_bonebol_bot" class="text-decoration-none"><strong>@sijati_bps_bonebol_bot</strong></a></li>
                    <li>Tap bot pada hasil pencarian</li>
                    <li>Klik start</li>
                </ul>
                <form action="{{ route('try.send.message') }}" method="POST" class="w-sm-50">
                    @csrf
                    <div class="mb-3">
                        <label for="telegram_id" class="form-label">Telegram Id</label>
                        <input type="text" class="form-control" name="telegram_id" id="telegram_id">
                        @error('telegram_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Pesan</label>
                        <textarea class="form-control" rows="3" name="pesan" id="pesan"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Kirim Pesan Uji Coba</button>
                </form>
            </div>
        </div>
    </div>
@endsection