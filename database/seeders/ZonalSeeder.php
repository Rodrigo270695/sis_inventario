<?php

namespace Database\Seeders;

use App\Models\Zonal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZonalSeeder extends Seeder
{

    public function run(): void
    {
        Zonal::create([
            'nombre'=>'CHICLAYO',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'CAJAMARCA',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'PIURA',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'TARAPOTO',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'IQUITOS',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'TRUJILLO',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'TUMBES',
            'unidad_negocio'=>'DAM',
        ]);
        Zonal::create([
            'nombre'=>'SAN JOSÉ',
            'unidad_negocio'=>'FRANQUICIA',
        ]);
    }
}
