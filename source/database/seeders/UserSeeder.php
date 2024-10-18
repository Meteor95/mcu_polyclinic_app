<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        DB::table('users')->insert([
        [
            'id' => 1,
            'uuid' => (string) Str::uuid(),
            'username' => "erayadigitalstudio",
            'email' => "hallo@eraydigital.co.id",
            'email_verified_at' => null,
            'password' => Hash::make("Salam1jiwa"),
            'remember_token' => null,
            'idhakakses' => 1,
            ],
        ]);  
    }
}
