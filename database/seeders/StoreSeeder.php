<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{

    public function run(): void
    {
        Store::create([
            "nombre"=> "Principal",
            "pdv_id"=> 1,
        ]);
        Store::create([
            "nombre"=> "Almacén I",
            "pdv_id"=> 1,
        ]);
        Store::create([
            "nombre"=> "Principal",
            "pdv_id"=> 2,
        ]);
    }
}
