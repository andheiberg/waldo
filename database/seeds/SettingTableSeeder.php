<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key' => 'branch',
            'value' => 'master'
        ]);

        DB::table('settings')->insert([
            'key' => 'env',
            'value' => null
        ]);
    }
}
