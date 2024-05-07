<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(["name"=> "Administrador",]);
        Role::create(["name"=> "Aprobador",]);
        Role::create(["name"=> "Supervisor 2",]);
        Role::create(["name"=> "Supervisor 1",]);
    }
}
