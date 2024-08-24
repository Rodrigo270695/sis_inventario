<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $admin = User::create([
            "name"=> "Rodrigo Granja R",
            "dni"=> "77344506",
            "pdv_id"=> 1,
            "email"=> "rodrigo_06_27@hotmail.com",
            "password"=> Hash::make("admin")
        ]);
        $admin->assignRole("Administrador");

        $admin = User::create([
            "name"=> "Victor Fernando",
            "dni"=> "06780073",
            "pdv_id"=> 1,
            "email"=> "victor.tume@grupomaclabi.com",
            "password"=> Hash::make("admin")
        ]);
        $admin->assignRole("Administrador");

        $admin = User::create([
            "name"=> "Juan Pérez",
            "dni"=> "12345678",
            "pdv_id"=> 1,
            "email"=> "juan_perez@hotmail.com",
            "password"=> Hash::make("12345678")
        ]);
        $admin->assignRole("Aprobador");

        $admin = User::create([
            "name"=> "Supervisor 1",
            "dni"=> "87654321",
            "pdv_id"=> 1,
            "email"=> "supervisor_1@hotmail.com",
            "password"=> Hash::make("87654321")
        ]);
        $admin->assignRole("Supervisor 1");

        $admin = User::create([
            "name"=> "Supervisor 2",
            "dni"=> "98765432",
            "pdv_id"=> 1,
            "email"=> "supervisor_2@hotmail.com",
            "password"=> Hash::make("98765432")
        ]);
        $admin->assignRole("Supervisor 2");

        $admin = User::create([
            "name"=> "Supervisor 11",
            "dni"=> "87654322",
            "pdv_id"=> 2,
            "email"=> "supervisor_11@hotmail.com",
            "password"=> Hash::make("87654322")
        ]);
        $admin->assignRole("Supervisor 1");

        $admin = User::create([
            "name"=> "Supervisor 22",
            "dni"=> "98765433",
            "pdv_id"=> 2,
            "email"=> "supervisor_22@hotmail.com",
            "password"=> Hash::make("98765433")
        ]);
        $admin->assignRole("Supervisor 2");
    }
}
