<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(!User::where('email', 'test@example.com')->exists()){
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => '1234567890',
            ]);
        }  
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');      
        Review::truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $products = Product::factory(20)->create();


     
    }
}
