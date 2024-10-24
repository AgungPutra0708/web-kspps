<?php

namespace Database\Seeders;

use App\Models\PetugasModel;
use App\Models\UserMemberModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPetugas = [
            'no_petugas' => 'P-0001',
            'nama_petugas' => 'Admin',
        ];

        // Menyimpan data ke tabel anggotas
        $id = PetugasModel::create($dataPetugas);

        $dataPetugasUser = [
            'id_user' => $id->id,
            'status' => "petugas",
            'username' => 'P0001',
            'password' => Hash::make('Superadminbmt123!'),
        ];

        UserMemberModel::create($dataPetugasUser);
    }
}
