<?php

namespace Database\Seeders;

use App\Helper\JadwalType;
use App\Models\Jadwal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jadwal::create([
            'type_jadwal' => JadwalType::ADZAN
        ]);
        Jadwal::create([
            'type_jadwal' => JadwalType::IMAM
        ]);
        Jadwal::create([
            'type_jadwal' => JadwalType::SENAM
        ]);
        Jadwal::create([
            'type_jadwal' => JadwalType::INFOGRAFIS
        ]);
        Jadwal::create([
            'type_jadwal' => JadwalType::KULTUM
        ]);
    }
}
