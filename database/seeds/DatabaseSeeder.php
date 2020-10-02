<?php

use Illuminate\Database\Seeder;
use App\Category;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $threads = [
           [
        		 'category_name' => 'Car',
        		 'parent_id' => '0',
        		],
        		[
        		 'category_name' => 'Bike',
        		 'parent_id' => '0',
        		],
        		[
        		 'category_name' => 'Truck',
        		 'parent_id' => '0',
        		],
        		[
        		 'category_name' => 'Bicycle',
        		 'parent_id' => '0',
        		],
        		[
        		 'category_name' => 'Auto',
        		 'parent_id' => '0',
        		],
        ];
 
        foreach ($threads as $thread)
            DB::table('categoryes')->insert($thread);
    }


}



