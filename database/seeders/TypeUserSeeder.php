<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_users')->insert([
            'name' => 'Administrador',
            'description' => 'All privileges granted',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('type_users')->insert([
            'name' => 'Estudiante',
            'description' => 'Regular student',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
