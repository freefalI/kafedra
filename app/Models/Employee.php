<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function getFullNameAttribute()
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->parent_name;
    }

    public function getUserFio()
    {
        return self::getFIO($this->name,$this->surname, $this->parent_name);
    }

    public function user()
    {
        return $this->belongsTo(Administrator::class, 'user_id');
    }

    public function scienceDegree()
    {
        return $this->belongsTo(ScienceDegree::class, 'science_degree_id');
    }

    public function academicTitle()
    {
        return $this->belongsTo(AcademicTitle::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public static function getFIO($name, $surname, $parent_name)
    {
        return $surname . ' ' . mb_substr($name, 0, 1) . '. ' . mb_substr($parent_name, 0, 1) . '.';
    }
}
