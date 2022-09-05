<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            array('id' => 1, 'name' => 'Mobiles','created_at' => date('Y-m-d H:i:s')),
            array('id' => 2, 'name' => 'Home Appliance','created_at' => date('Y-m-d H:i:s')),
            array('id' => 3, 'name' => 'Home Decor','created_at' => date('Y-m-d H:i:s')),
            array('id' => 4, 'name' => 'Shoes','created_at' => date('Y-m-d H:i:s'))];
        Category::insert($categories); //dummy categories.
    }
}
