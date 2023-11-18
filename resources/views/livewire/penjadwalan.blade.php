<div>
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
            <div class="alert alert-success alert-dismissible mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h5 class="card-header">List Penjadwalan {{App\Models\Jadwal::find($jadwal_id)->type_jadwal}}</h5>
        <div class="d-flex align-items-center justify-content-start gap-3 mx-3 flex-wrap mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">+ Tambah Penjadwalan {{App\Models\Jadwal::find($jadwal_id)->type_jadwal}}</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#setDateModal">{{ App\Models\Penjadwalan::first() && App\Models\Penjadwalan::first()->tanggal_jadwal ? 'Reset Tanggal Jadwal' : 'Atur Tanggal Jadwal' }}</button>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th>Urutan Jadwal</th>
                        <th>Nama Pelaksana</th>
                        <th>Tipe Jadwal</th>
                        <th>Waktu Pelaksanaan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($penjadwalans as $penjadwalan)
                        <tr class="text-center">
                            <td>{{ $penjadwalan->urutan }}</td>
                            <td>{{ $penjadwalan->user->name }}</td>
                            <td>{{ $penjadwalan->jadwal->type_jadwal }}</td>
                            <td>{{ $penjadwalan->tanggal_jadwal ? Carbon\Carbon::parse($penjadwalan->tanggal_jadwal)->translatedFormat('l, d F Y') : '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <div wire:click='toUp({{ $penjadwalan->id }})' class="dropdown-item cursor-pointer {{ $penjadwalan->urutan == 1 ? 'disabled' : '' }}">
                                            <i class="mdi mdi-arrow-up-drop-circle-outline me-2"></i> Pindah Keatas
                                        </div>
                                        <div wire:click='toDown({{ $penjadwalan->id }})' class="dropdown-item cursor-pointer {{ $penjadwalan->urutan == count($penjadwalans) ? 'disabled' : '' }}">
                                            <i class="mdi mdi-arrow-down-drop-circle-outline me-2"></i> Pindah Kebawah
                                        </div>
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
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <div class="modal fade" id="setDateModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="setDateModalTitle">Penjadwalan</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xl">
                                    <div class="card-body">
                                        <form id="formCreateAccount" wire:submit.prevent='setScheduleDate'>
                                            @csrf
                                            <div class="form-text">Atur Tanggal Jadwal</div>
                                            <div class="form-floating form-floating-outline mb-4">
                                                <input type="date" class="form-control" wire:model='tanggal_jadwal'>
                                            </div>
                                            <div class="mb-4"></div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Tutup
                                                </button>
                                                <button type="submit" class="btn btn-primary">Simpan Tanggal</button>
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
</div>
@push('scripts')
    <script>
        $(window).on('load', function() {
            Livewire.on('dismiss-modal', function() {
                $('#setDateModal').modal('hide')
            })
        })
    </script>
@endpush