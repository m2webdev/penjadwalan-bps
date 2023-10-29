@extends('dashboard.user')
@section('title', 'dashboard')

@section('content')
    <div class="tab-content p-0">
        @foreach ($jadwals as $jadwal)
            <div class="tab-pane fade {{ $loop->iteration == 1 ? 'active show' : '' }}"
                id="navs-tab-{{ $jadwal->id }}" role="tabpanel">
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                    <label for="html5-date-input">Pilih Berdasarkan Waktu Pelaksanaan</label>
                    <input class="form-control date-input" type="date" id="date-{{ $jadwal->id }}"
                        data-jadwal-id="{{ $jadwal->id }}" />
                </div>

                <div class="card">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="tables-{{$jadwal->id}}">
                            <thead id="thead-{{$jadwal->id}}">
                                <tr>
                                    <th>No.</th>
                                    <th>Waktu Pelaksanaan</th>
                                    <th>Pelaksana</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="jadwal-table-{{ $jadwal->id }}">
                                @foreach ($penjadwalan as $item)
                                    @if ($item->jadwal_id == $jadwal->id)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $item->tanggal_jadwal }}</td>
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
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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

    <!-- Add this script after including jQuery -->


@endsection
