<div class="card">
    <div class="card-header">
        <h5 class="card-title">Laporan Penjadwalan Karyawan</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <button wire:click='templateLaporan' class="btn w-50 rounded-0 {{ !$is_custom ? 'btn-primary' : 'btn-outline-primary' }}">Laporan Template</button>
            <button wire:click='customLaporan' class="btn w-50 rounded-0 {{ $is_custom ? 'btn-primary' : 'btn-outline-primary' }}">Laporan Kustom</button>
        </div>
        @if ($is_custom)
            <form wire:submit.prevent='' class="w-sm-50">
                @csrf
                <div class="mb-3">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" wire:model.live='dari_tanggal'>
                </div>
                <div class="mb-3">
                    <label for="date_until" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_until" wire:model.live='sampai_tanggal'>
                </div>
                <div class="mb-3">
                    <label for="tipe_jadwal" class="form-label">Tipe Jadwal</label>
                    <select name="tipe_jadwal" id="tipe_jadwal" class="form-select" wire:model.live='tipe_jadwal'>
                        <option value="semua">Semua</option>
                        @foreach ($allJadwalType as $jadwal)
                            <option value="{{ $jadwal->type_jadwal }}">{{ $jadwal->type_jadwal }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Download Laporan PDF</button>
            </form>
        @else
            <form wire:submit.prevent='' class="w-sm-50">
                @csrf
                <div class="mb-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select" wire:model.live='tahun'>
                        <option value="semua">Semua</option>
                        @foreach ($allTahun as $tahun)
                            <option value="{{ $tahun->tahun }}">{{ $tahun->tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select" wire:model.live='bulan' wire:loading.class='disabled' wire:target='tahun'>
                        <option value="semua">Semua</option>
                        @foreach ($allBulan as $bulan)
                            <option value="{{ $bulan->bulan }}">{{ $bulan->bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tipe_jadwal" class="form-label">Tipe Jadwal</label>
                    <select name="tipe_jadwal" id="tipe_jadwal" class="form-select" wire:model.live='tipe_jadwal'>
                        <option value="semua">Semua</option>
                        @foreach ($allJadwalType as $jadwal)
                            <option value="{{ $jadwal->type_jadwal }}">{{ $jadwal->type_jadwal }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Download Laporan PDF</button>
            </form>
        @endif
    </div>
</div>