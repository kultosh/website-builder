<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }
}
