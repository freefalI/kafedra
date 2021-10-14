<?php

namespace Database\Seeders;

use App\Models\AcademicTitle;
use App\Models\Position;
use App\Models\ScienceDegree;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScienceDegree::create([
            'short_title' => 'ктн',
            'title' => 'Кандидат технічних наук',
            'type' => 'candidat'
        ]);
        ScienceDegree::create([
            'short_title' => 'кен',
            'title' => 'Кандидат економічних наук',
            'type' => 'candidat'
        ]);
        ScienceDegree::create([
            'short_title' => 'кфмн',
            'title' => 'Кандидат фізико-математичних наук',
            'type' => 'candidat'
        ]);


        ScienceDegree::create([
            'short_title' => 'дтн',
            'title' => 'Доктор технічних наук',
            'type' => 'doctor'
        ]);
        ScienceDegree::create([
            'short_title' => 'ден',
            'title' => 'Доктор економічних наук',
            'type' => 'doctor'
        ]);
        ScienceDegree::create([
            'short_title' => 'дфмн',
            'title' => 'Доктор фізико-математичних наук',
            'type' => 'doctor'
        ]);


        ScienceDegree::create([
            'short_title' => 'PhD',
            'title' => 'Доктор філософії',
            'type' => 'phd'
        ]);



        AcademicTitle::create([
            'short_title' => 'снс',
            'title' => 'Старший науковий співробітник',
        ]);
        AcademicTitle::create([
            'short_title' => 'доц',
            'title' => 'Доцент',
        ]);
        AcademicTitle::create([
            'short_title' => 'проф',
            'title' => 'Професор',
        ]);

        Position::create([
            'title' => 'Асистент',
        ]);
        Position::create([
            'title' => 'Старший викладач',
        ]);
        Position::create([
            'title' => 'Доцент',
        ]);
        Position::create([
            'title' => 'Професор',
        ]);
        Position::create([
            'title' => 'Завідувач кафедри',
        ]);
    }
}
