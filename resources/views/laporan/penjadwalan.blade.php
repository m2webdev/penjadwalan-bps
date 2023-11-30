<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body style="margin: {{ $type && $type == App\Helper\JadwalType::INFOGRAFIS ? '2cm' : '1cm' }}">
    @foreach ($penjadwalan as $key => $value)
        @if($type && $type !== App\Helper\JadwalType::INFOGRAFIS && $loop->iteration > 1)
            <div class="page-break"></div>
        @endif
        @if($type && $type !== App\Helper\JadwalType::INFOGRAFIS)
        <h6 class="mb-4">Bulan {{ $key }}</h6>
        @endif
        @foreach ($value as $type => $jadwal)
            @php($tipe_jadwal = strtolower($type))
            @if($tipe_jadwal == strtolower(App\Helper\JadwalType::INFOGRAFIS))
                @foreach ($jadwal['all'] as $item)
                    @if($item->infografis)
                        @if ($loop->iteration > 1)
                            <div class="page-break"></div>
                        @endif
                        @if($item->infografis->gambar)
                            <img src="data:image;base64,{{ base64_encode(file_get_contents(public_path('storage/infografis/' . $item->infografis->gambar))) }}" alt="{{ $item->infografis->judul }}" width="288" height="384" style="object-fit: cover; transform:translateX(50%)" class="mb-3">
                        @endif
                        <h2 style="text-transform: uppercase; color: #6FB729;" class="fw-bold">{{ $item->infografis->judul }}</h2>
                        {!! html_entity_decode($item->infografis->isi) !!}
                        <div class="mt-5">
                            <h3><span style="color: #EA8B14">Infografis</span><span class="mx-3">|</span><span style="color: #6FB729; text-transform: capitalize;">{{ $item->user->name }}</span></h3>
                        </div>
                    @endif
                @endforeach
            @elseif($tipe_jadwal == strtolower(App\Helper\JadwalType::KULTUM))
            @else
                <table class="table" style="border-collapse: collapse;">
                    <thead style="vertical-align: middle;" class="text-center">
                        <tr>
                            <th style="border: 1px solid #000; padding: 8px; font-weight: bold; font-size: 14px; width: 6%;" rowspan="2" scope="col">No</th>
                            <th style="border: 1px solid #000; padding: 8px; font-weight: bold; font-size: 14px;" rowspan="2" scope="col">Nama Pegawai</th>
                            <th colspan="{{ count($jadwal['all']) }}" style="text-transform: capitalize; text-align: center; border: 1px solid #000; padding: 8px; font-weight: bold; font-size: 14px;" scope="col">Jadwal {{ $type }}</th>
                        </tr>
                        <tr>
                            @for ($i = 0; $i < count($jadwal['all']); $i++)
                                <th style="border: 1px solid #000; padding: 8px; font-weight: bold; font-size: 14px;" scope="col">{{ Carbon\Carbon::parse($jadwal['all'][$i]->tanggal_jadwal)->format('d') }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal['all'] as $item)
                            <tr>
                                <td style="border: 1px solid #000; padding: 8px; width: 6%;" class="text-center">{{ $loop->iteration }}</td>
                                <td style="border: 1px solid #000; padding: 8px;">{{ $item->user->name }}</td>
                                @for ($i = 0; $i < count($jadwal['all']); $i++)
                                    <td style="border: 1px solid #000; padding: 8px; {{ Carbon\Carbon::parse($jadwal['all'][$i]->tanggal_jadwal)->format('d') == Carbon\Carbon::parse($item->tanggal_jadwal)->format('d') ? 'background-color: #0078B3;' : '' }}"></td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endforeach
</body>
</html>