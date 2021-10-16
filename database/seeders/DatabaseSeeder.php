<?php

namespace Database\Seeders;

use App\Models\AcademicTitle;
use App\Models\Employee;
use App\Models\Position;
use App\Models\ScienceDegree;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //TODO create show add to permissions

        ini_set('memory_limit', '-1');
        DB::unprepared(file_get_contents("database/roles.sql"));

        // \App\Models\User::factory(10)->create();

        // $userModel = config('admin.database.users_model');

        // if ($userModel::count() == 0) {
        // $this->call( \Encore\Admin\Auth\Database\AdminTablesSeeder::class);
        // }
        $this->call(LabelSeeder::class);

        \App\Models\Employee::factory(60)->make()->each(function ($item, $key) {
            $user = \App\Models\Administrator::create(['username' => 'user' . ($key + 1), 'name' => $item->getUserFio(), 'password' => bcrypt('password')]);

            DB::table('admin_role_users')->insert(['user_id' => $user->id, 'role_id' => 2]);

            $item->user_id = $user->id;

            $item->science_degree_id = ScienceDegree::inRandomOrder()->first()->id;
            $item->academic_title_id = AcademicTitle::inRandomOrder()->first()->id;
            $item->position_id = Position::inRandomOrder()->where('title', '!=', 'Завідувач кафедри')->first()->id;

            $item->save();
        });

        \App\Models\Student::factory(100)->create();

        \App\Models\Work::factory(200)->create();
        \App\Models\Certification::factory(50)->create();

        \App\Models\Work::each(function ($item) {
            $item->update(['employee_id' => Employee::inRandomOrder()->first()->id]);
        });

        \App\Models\Certification::each(function ($item) {
            $item->update(['employee_id' => Employee::inRandomOrder()->first()->id]);
        });


        Employee::inRandomOrder()->limit(1)
            ->update(['position_id' => Position::where('title', 'Завідувач кафедри')->first()->id]);


        \App\Models\Leave::factory(10)->create();
    }
}
