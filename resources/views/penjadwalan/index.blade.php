@extends('dashboard.index')
@section('title', 'Penjadwalan')

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
                    <div class="badge bg-danger rounded-pill ms-auto">5</div>
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
            <li class="menu-item open">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-application-outline"></i>
                    <div data-i18n="Authentications">Pengaturan jadwal</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('jadwal.index') }}" class="menu-link">
                            <div data-i18n="Account">Jadwal</div>
                        </a>
                    </li>
                    @foreach (App\Models\Jadwal::all() as $jadwal)
                        <li class="menu-item">
                            <a href="{{ route('penjadwalan.jadwal', ['id' => $jadwal->id]) }}" class="menu-link">
                                <div data-i18n="Basic">{{ $jadwal->type_jadwal }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
    </aside>
@endsection

@section('content')
    <div style="margin-top: 4%" class="card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h5 class="card-header">List Penjadwalan {{App\Models\Jadwal::find($jadwal_id)->type_jadwal}}</h5>
        <button style="margin-left: 1%;" type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#modalCenter">+ Tambah Penjadwalan {{App\Models\Jadwal::find($jadwal_id)->type_jadwal}}</button>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelaksana</th>
                        <th>Tipe Jadwal</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($penjadwalans as $penjadwalan)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ App\Models\User::find($penjadwalan->user_id)->name }}</td>
                            <td>{{ App\Models\Jadwal::find($penjadwalan->jadwal_id)->type_jadwal }}</td>
                            <td>{{ $penjadwalan->tanggal_jadwal }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a type="button" class="dropdown-item" href="javascript:void(0);"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit{{ $penjadwalan->id }}">
                                            <i class="mdi mdi-pencil-outline me-2"></i> Edit
                                        </a>
                                        <a type="button" class="dropdown-item" href="javascript:void(0);"
                                            data-bs-toggle="modal" data-bs-target="#modalDelete{{ $penjadwalan->id }}">
                                            <i class="mdi mdi-trash-can-outline me-2"></i> Delete
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="mt-3">
                                        <div class="modal fade" id="modalDelete{{ $penjadwalan->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="modalCenterTitle">Hapus Penjadwalan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-xl">
                                                                <div class="card-body">
                                                                    <form
                                                                        action="{{ route('penjadwalan.delete',  ['id' => $penjadwalan->id, 'jadwal' => App\Models\Jadwal::find($jadwal_id)])  }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-bs-dismiss="modal">
                                                                                Tutup
                                                                            </button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Hapus</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="mt-3">
                                        <div class="modal fade" id="modalEdit{{ $penjadwalan->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="modalCenterTitle">Penjadwalan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-xl">
                                                                <div class="card-body">
                                                                    <form
                                                                        action="{{ route('penjadwalan.update', ['id' => $penjadwalan->id, 'jadwal' => App\Models\Jadwal::find($jadwal_id)]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div class="form-text">Nama Pelaksana</div>
                                                                        <div
                                                                            class="form-floating form-floating-outline mb-4">
                                                                            <select id="basic-default-role"
                                                                                class="form-select" name="user">
                                                                                @foreach ($pengguna as $user)
                                                                                    <option value="{{ $user->id }}"
                                                                                        {{ $user->id === App\Models\User::find($penjadwalan->user_id)->id ? 'selected' : '' }}>
                                                                                        {{ $user->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-text">Waktu Pelaksanaan</div>
                                                                        <div class="row mb-3">
                                                                            <div
                                                                                class="form-floating form-floating-outline mb-4">
                                                                                <input class="form-control"
                                                                                    type="datetime-local"
                                                                                    value={{ $penjadwalan->tanggal_jadwal }}
                                                                                    id="html5-datetime-input"
                                                                                    name="penjadwalan_tambah" required />
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-4"></div>

                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-bs-dismiss="modal">
                                                                                Tutup
                                                                            </button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Simpan
                                                                                Perubahan</button>
                                                                        </div>
                                                                    </form>
                                                                    <div id="success-warning"
                                                                        class="alert alert-warning alert-dismissible"
                                                                        role="alert" style="display: none;">
                                                                        Harap Mengisi Semua Data Yang Ada Dengan Benar
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modalCenterTitle">Penjadwalan</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xl">
                                    <div class="card-body">
                                        <form id="formCreateAccount" action="{{ route('penjadwalan.create', ['jadwal' => App\Models\Jadwal::find($jadwal_id)]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-text">Nama Pelaksanaan</div>
                                            <div class="form-floating form-floating-outline mb-4">
                                                <select id="basic-default-role" class="form-select" name="user_tambah">
                                                    @foreach ($pengguna as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-text">Waktu Pelaksanaan</div>
                                            <div class="row mb-3">
                                                <div class="form-floating form-floating-outline mb-4">
                                                    <input class="form-control" type="datetime-local"
                                                        id="html5-datetime-input" name="penjadwalan_tambah" required />
                                                </div>
                                            </div>
                                            <div class="mb-4"></div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Tutup
                                                </button>
                                                <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
                                            </div>
                                        </form>
                                        <div id="success-warning" class="alert alert-warning alert-dismissible"
                                            role="alert" style="display: none;">
                                            Harap Mengisi Semua Data Yang Ada Dengan Benar
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Layout -->

@endsection
