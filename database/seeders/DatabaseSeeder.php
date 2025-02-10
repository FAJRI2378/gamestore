<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
      // Seeder dengan nilai numerik
$users = [
    [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'type' => 1, // admin
        'password' => bcrypt('123456'),
    ],
    [
        'name' => 'User',
        'email' => 'user@gmail.com',
        'type' => 0, // user
        'password' => bcrypt('123456'),
    ],
];

        // Menambahkan setiap pengguna ke dalam tabel 'users'
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
