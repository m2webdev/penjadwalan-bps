@extends('dashboard.user')
@section('title', 'dashboard')

@section('content')
    <div class="card">
        <div class="tab-content">
            @foreach ($jadwals as $jadwal)
                <div class="tab-pane fade {{ $loop->iteration == 1 ? 'active show' : '' }}"
                    id="navs-tab-{{ $jadwal->id }}" role="tabpanel">
                    <div class="table-responsive text-nowrap">
                        <table class="table border border-2 table-striped" style="--bs-table-striped-bg: #55A5CC; --bs-table-striped-color: white; --bs-table-border-color: #55A5CC;" id="tables-{{$jadwal->id}}">
                            <thead id="thead-{{$jadwal->id}}">
                                <tr>
                                    <th>No.</th>
                                    <th>Waktu Pelaksanaan</th>
                                    <th>Pelaksana</th>
                                    @if($jadwal->type_jadwal == App\Helper\JadwalType::KULTUM)
                                        <th>Kultum</th>
                                    @elseif($jadwal->type_jadwal == App\Helper\JadwalType::INFOGRAFIS)
                                        <th>Infografis</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="jadwal-table-{{ $jadwal->id }}">
                                @foreach (\App\Models\Penjadwalan::where('jadwal_id', $jadwal->id)->orderBy('tanggal_jadwal', 'DESC')->get() as $item)
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
                                        @if($jadwal->type_jadwal == App\Helper\JadwalType::KULTUM)
                                            <td>
                                                <a href="{{ $item->kultum ? route('show.kultum', ['kultum' => $item->kultum_id]) : '#' }}" class="{{ $loop->iteration % 2 == 1 ? 'text-white' : 'text-primary' }}">
                                                    @if($item->kultum)
                                                        {{ $item->kultum->judul }}
                                                        <i class="mdi mdi-arrow-top-right-bold-box-outline ms-2"></i>
                                                    @else
                                                        Tidak ada kultum
                                                    @endif
                                                </a>
                                            </td>
                                        @elseif($jadwal->type_jadwal == App\Helper\JadwalType::INFOGRAFIS)
                                            <td>
                                                <a href="{{ $item->infografis ? route('show.infografis', ['infografis' => $item->infografis_id]) : '#' }}" class="{{ $loop->iteration % 2 == 1 ? 'text-white' : 'text-primary' }}">
                                                    @if($item->infografis)
                                                        {{ $item->infografis->judul }}
                                                        <i class="mdi mdi-arrow-top-right-bold-box-outline ms-2"></i>
                                                    @else
                                                        Tidak ada infografis
                                                    @endif
                                                </a>
                                            </td>
                                        @endif
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
