<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('email_templates')->truncate();
        $path = base_path() . '/database/seeders/dump/EmailTemplate.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
