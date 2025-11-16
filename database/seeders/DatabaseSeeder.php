<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
     public function run(): void
    {
        // Ambil role_id berdasarkan nama role
        // $guruRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        $siswaRoleId = DB::table('roles')->where('name', 'User')->value('id');

        // Buat user guru
        // User::updateOrCreate(
        //     ['email' => 'guru@gmail.com'],
        //     [
        //         'name' => 'guru',
        //         'password' => Hash::make('gurupassword'),
        //         'role_id' => $guruRoleId,
        //     ]
        // );

        // Buat user siswa
        User::updateOrCreate(
            ['email' => 'doni@gmail.com'],
            [
                'name' => 'doni',
                'password' => Hash::make('siswaadminrawr'),
                'role_id' => $siswaRoleId,
            ]
        );
    }
}
