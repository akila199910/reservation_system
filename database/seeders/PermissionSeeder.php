<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $action = ['Read','Create','Update','Delete'];
        $permissions = ['Client', 'Preference','CafeTable','Reservation','Notification', 'Review','Report','Users', 'Location', 'Floor_Plan','intake'];

        $insert_data = [];
        foreach ($permissions as $key => $value) {
            foreach($action as $act_key => $act_value)
            {
                $insert_data[] = [
                    'name' => $act_value."_".$value,
                    'guard_name' => 'web',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ];
            }
        }

        DB::table('permissions')->insert($insert_data);
    }
}
