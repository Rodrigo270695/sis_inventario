<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{

    public function run(): void
    {
        EquipmentType::create([
            "nombre"=> "PC/Laptop",
        ]);
    }
}
