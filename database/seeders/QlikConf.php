<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QlikConf extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $mod = [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Qlik Configuration',
                'icon' => 'fa fa-cog',
                'path' => 'qlik_confs',
                'table_name' => 'qlik_confs',
                'controller' => 'QlikConfController',
                'is_protected' => 1,
                'is_active' => 1,
            ];
        DB::table('cms_moduls')->insert($mod);     
    }
}
//php artisan db:seed -–class=QlikConf