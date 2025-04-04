<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $u1 = new User();
        $u1->name = "Admin";
        $u1->email = "admin@mail.com";
        $u1->password = bcrypt("admin12345");
        $u1->save();
    }
}
