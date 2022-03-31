<?php


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           
           'role-list',
           'role-create',
           'role-edit',
           'role-delete'
           
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
   
        // $faker = Faker::create();
        // foreach(range(1,100) as $value) {
        //     DB::table('whatsapp_chat')->insert([
        //         'from' => '+14155238886',
        //         'to' => $faker->randomElement(['+923223275827', '+923030560951' , '+923007000800']),
        //         'body' => $faker->sentence(30),
        //         'num_media' => 0,
        //         'media_url' => null,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        // }
    }
}