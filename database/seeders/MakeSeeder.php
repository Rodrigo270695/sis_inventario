<?php

namespace Database\Seeders;

use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MakeSeeder extends Seeder
{

    public function run(): void
    {
        Make::create([
            "nombre"=> "HP",
            "equipment_type_id"=> 1,
        ]);
        Make::create([
            "nombre"=> "Lenovo",
            "equipment_type_id"=> 1,
        ]);
    }
}
