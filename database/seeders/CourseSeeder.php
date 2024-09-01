<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # utilizando factory com fake
        Course::factory(10)->create();

        // if (!Course::where('name', 'curso de laravel v1')->first()) {
        //     Course::create([
        //         'name' => 'curso de laravel v1'
        //     ]);
        // }
        // if (!Course::where('name', 'curso de laravel v2')->first()) {
        //     Course::create([
        //         'name' => 'curso de laravel v2'
        //     ]);
        // }
        // if (!Course::where('name', 'curso de laravel v3')->first()) {
        //     Course::create([
        //         'name' => 'curso de laravel v3'
        //     ]);
        // }
        // if (!Course::where('name', 'curso de laravel v4')->first()) {
        //     Course::create([
        //         'name' => 'curso de laravel v4'
        //     ]);
        // }
    }
}
