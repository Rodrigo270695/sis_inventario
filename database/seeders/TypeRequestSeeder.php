<?php

namespace Database\Seeders;

use App\Models\TypeRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeRequest::create([
            "nombre"=> "COMPRAR",
        ]);
        TypeRequest::create([
            "nombre"=> "DAR BAJA",
        ]);
        TypeRequest::create([
            "nombre"=> "MANTENIMIENTO",
        ]);
    }
}
