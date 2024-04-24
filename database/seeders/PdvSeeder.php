<?php

namespace Database\Seeders;

use App\Models\Pdv;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PdvSeeder extends Seeder
{

    public function run(): void
    {
        Pdv::create([
            "nombre"=> "Chiclayo Ad",
            "zonal_id"=> 1,
        ]);
        Pdv::create([
            "nombre"=> "San José I",
            "zonal_id"=> 8,
        ]);
    }
}
