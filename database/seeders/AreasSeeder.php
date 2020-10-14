<?php

namespace Database\Seeders;

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
                'modality_id' => $career['modality_id']
            ]);
        }

        // DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
