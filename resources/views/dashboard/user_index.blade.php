@extends('dashboard.user')
@section('title', 'dashboard')

@section('content')
    <div class="card content-pane">
        <div class="card-body">
            @foreach ($jadwals as $jadwal)
                <div class="tab-pane fade {{ $loop->iteration == 1 ? 'active show' : '' }}"
                    id="navs-tab-{{ $jadwal->id }}" role="tabpanel">
                    <div class=" mb-5">
                        <label for="date-{{ $jadwal->id }}">Pilih Berdasarkan Waktu Pelaksanaan</label>
                        <input class="form-control date-input" type="date" id="date-{{ $jadwal->id }}"
                            data-jadwal-id="{{ $jadwal->id }}" />
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table border border-2 table-striped" style="--bs-table-striped-bg: #55A5CC; --bs-table-striped-color: white; --bs-table-border-color: #55A5CC;" id="tables-{{$jadwal->id}}">
                            <thead id="thead-{{$jadwal->id}}">
                                <tr>
                                    <th>No.</th>
                                    <th>Waktu Pelaksanaan</th>
                                    <th>Pelaksana</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="jadwal-table-{{ $jadwal->id }}">
                                @foreach (\App\Models\Penjadwalan::where('jadwal_id', $jadwal->id)->orderBy('urutan')->get() as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $item->tanggal_jadwal ? Carbon\Carbon::parse($item->tanggal_jadwal)->translatedFormat('l, d F Y') : '-' }}</td>
                                        <td>
                                            <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                data-bs-placement="top">
                                                <img src="{{ asset('style/assets/img/avatars/' . ($item->jk === 'laki-laki' ? '5' : '6') . '.png') }}"
                                                    alt="Avatar" class="rounded-circle avatar avatar-xs pull-up"
                                                    title="{{ $item->name }}">
                                                {{ App\Models\User::find($item->user_id)->name }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @push('js')
                    <script>
                        $(document).ready(function() {
                            $("#date-{{ $jadwal->id }}").on("change", function() {
                                var selectedDate = $(this).val();
                                var jadwalId = $(this).data("jadwal-id");
    
                                $("#jadwal-table-" + jadwalId + " tr").hide();
    
                                $("#jadwal-table-" + jadwalId + " tr").each(function() {
                                    var rowDate = $(this).find("td:nth-child(2)").text().split(" ")[
                                    0]; // Get the date part only
                                    if (rowDate === selectedDate) {
                                        $(this).show();
                                    }
                                });
                            });
                        });
                    </script>
                @endpush
            @endforeach
        </div>
    </div>
@endsection
