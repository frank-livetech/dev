<?php
  
use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        	'name' => 'Muhammad Kashif', 
        	'email' => 'muhammadkashif70000@gmail.com',
        	'password' => bcrypt('Kashif@999'),
        	'status' => '1',
        	'user_type' => '1',
        	'is_support_staff' => '1',
        ]);

        $user = User::create([
        	'name' => 'Frank Zabroski', 
        	'email' => 'frank@mylive-tech.com',
        	'password' => bcrypt('Yhi@123nmjxs'),
        	'status' => '1',
        	'user_type' => '1',
        	'is_support_staff' => '1',
        ]);

        $user = User::create([
        	'name' => 'Ludy Chacon', 
        	'email' => 'ludy@mylive-tech.com',
        	'password' => bcrypt('Yhi@123nmjxs'),
        	'status' => '1',
        	'user_type' => '1',
        	'is_support_staff' => '1',
        ]);

        $adminPermData = array(

            array("feature_id" => '1',"role_id" => '1'),
            array("feature_id" => '2',"role_id" => '1'),
            array("feature_id" => '3',"role_id" => '1'),
            array("feature_id" => '4',"role_id" => '1'),
            array("feature_id" => '5',"role_id" => '1'),
            array("feature_id" => '6',"role_id" => '1'),
            array("feature_id" => '7',"role_id" => '1'),
            array("feature_id" => '8',"role_id" => '1'),
            array("feature_id" => '9',"role_id" => '1'),
            array("feature_id" => '10',"role_id" => '1'),
            array("feature_id" => '11',"role_id" => '1'),
            array("feature_id" => '12',"role_id" => '1'),
            array("feature_id" => '13',"role_id" => '1'),
            array("feature_id" => '14',"role_id" => '1'),
            array("feature_id" => '15',"role_id" => '1'),
            array("feature_id" => '16',"role_id" => '1'),
            array("feature_id" => '17',"role_id" => '1'),
            array("feature_id" => '18',"role_id" => '1'),
            array("feature_id" => '19',"role_id" => '1'),
            array("feature_id" => '20',"role_id" => '1'),
            array("feature_id" => '21',"role_id" => '1'),
            array("feature_id" => '22',"role_id" => '1'),
            array("feature_id" => '23',"role_id" => '1'),
            array("feature_id" => '24',"role_id" => '1'),
            array("feature_id" => '25',"role_id" => '1'),
            array("feature_id" => '26',"role_id" => '1'),
            array("feature_id" => '27',"role_id" => '1'),
            array("feature_id" => '28',"role_id" => '1'),
            array("feature_id" => '29',"role_id" => '1'),
            array("feature_id" => '30',"role_id" => '1'),
            array("feature_id" => '31',"role_id" => '1'),
            array("feature_id" => '32',"role_id" => '1'),
            array("feature_id" => '33',"role_id" => '1'),
            array("feature_id" => '34',"role_id" => '1'),
            array("feature_id" => '35',"role_id" => '1'),
            array("feature_id" => '36',"role_id" => '1'),
            array("feature_id" => '37',"role_id" => '1'),
            array("feature_id" => '38',"role_id" => '1')
 
        );

        foreach($adminPermData as $key => $value) {

            DB::table("role_has_permission")->insert([
                $key => $value,
            ]);

        }

    }
}