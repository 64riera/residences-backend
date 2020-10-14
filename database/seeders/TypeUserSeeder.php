<?php

namespace Database\Seeders;

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
            'name' => 'Admin',
            'description' => 'All privileges granted'
        ]);

        DB::table('type_users')->insert([
            'name' => 'Student',
            'description' => 'Regular student'
        ]);
    }
}
