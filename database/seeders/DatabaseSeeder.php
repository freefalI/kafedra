<?php

namespace Database\Seeders;

use App\Models\AcademicTitle;
use App\Models\Employee;
use App\Models\Position;
use App\Models\ScienceDegree;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // $userModel = config('admin.database.users_model');

        // if ($userModel::count() == 0) {
        // $this->call( \Encore\Admin\Auth\Database\AdminTablesSeeder::class);
        // }
        //TODO hardcode menu here, and seed few users(admins


        // \App\Models\Employee::factory(1)->create()
        //         ->each(function($item){
        //             $user = \App\Models\User::factory()->create(['name'=>'-','password'=>bcrypt('test'),'employee_id'=>$item->id]);
        //         });


        // $this->call( LabelSeeder::class);

        //TODO seed relation between employee and labels


        Employee::each(function ($item) {
            $item->science_degree_id = ScienceDegree::inRandomOrder()->first()->id;
            $item->academic_title_id = AcademicTitle::inRandomOrder()->first()->id;
            $item->position_id = Position::inRandomOrder()->where('title','!=','Завідувач кафедри')->first()->id;
            $item->save();
        });
        Employee::inRandomOrder()->limit(1)
        ->update(['position_id'=>Position::where('title','Завідувач кафедри')->first()->id]);
    }
}
