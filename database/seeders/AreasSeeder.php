<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Careers table **/
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('careers')->truncate();

        $careers = [
            [
                'id' => 1,
                'name' => 'Ing.Administración',
                'description' => 'Ingenieria en Administración',
                'is_active' => true,
                'modality_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'Ing.Agronomía',
                'description' => 'Ingeniería En Agronomía',
                'is_active' => true,
                'modality_id' => 1
            ],
            [
                'id' => 3,
                'name' => 'Ing. en gestión empresarial',
                'description' => 'Ingenieria En Gestion Empresarial',
                'is_active' => true,
                'modality_id' => 1
            ],
            [
                'id' => 4,
                'name' => 'Ing. en industrias alimentarias',
                'description' => 'Ingenieria En Industrias Alimentarias',
                'is_active' => true,
                'modality_id' => 1
            ],
            [
                'id' => 5,
                'name' => 'Ing.Informática',
                'description' => 'Ingeniería Informática',
                'is_active' => true,
                'modality_id' => 1
            ],
            [
                'id' => 6,
                'name' => 'Ing.Informática',
                'description' => 'Ingeniería Informática',
                'is_active' => true,
                'modality_id' => 2
            ],
            [
                'id' => 7,
                'name' => 'Ing.Logística',
                'description' => 'Ingeniería Logística',
                'is_active' => true,
                'modality_id' => 1
            ],
        ];

        foreach ($careers as $career) {
            DB::table('careers')->insert([
                'name' => $career['name'],
                'description' => $career['description'],
                'is_active' => $career['is_active'],
                'modality_id' => $career['modality_id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        /** Admin areas table **/
        DB::table('admin_areas')->truncate();
        
        $adminAreas = [
            [
                'id' => 1,
                'name' => 'Docente',
                'description' => 'Profesor que imparte una materia',
                'is_active' => 1
            ],
            [
                'id' => 2,
                'name' => 'Director general',
                'description' => 'Director de la institución',
                'is_active' => 1
            ],
            [
                'id' => 3,
                'name' => 'División de estudios profesionales',
                'description' => 'Estudios profesionales',
                'is_active' => 1
            ],
            [
                'id' => 4,
                'name' => 'Auxiliar de división de estudios profesionales',
                'description' => 'Estudios profesionales',
                'is_active' => 1
            ],
            [
                'id' => 5,
                'name' => 'Coordinación de las carreras - Agronomía e Informática',
                'description' => 'Agronomía e informática',
                'is_active' => 1
            ],
            [
                'id' => 6,
                'name' => 'Coordinación de las carreras - Logística e industrias alimentarias',
                'description' => 'Logística e industrias alimentarias',
                'is_active' => 1
            ],
            [
                'id' => 7,
                'name' => 'Coordinación de las carreras - Administración y gestión empresarial',
                'description' => 'Administración y gestión empresarial',
                'is_active' => 1
            ],
            [
                'id' => 8,
                'name' => 'Coordinación de titulación',
                'description' => 'Coordinación de titulación',
                'is_active' => 1
            ]
        ];

        foreach ($adminAreas as $area) {
            DB::table('admin_areas')->insert([
                'name' => $area['name'],
                'description' => $area['description'],
                'is_active' => $area['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        // DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
