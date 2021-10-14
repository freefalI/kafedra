<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScienceDegree extends Model
{
    use HasFactory;

    protected $fillable = ['short_title','title','type'];
}
