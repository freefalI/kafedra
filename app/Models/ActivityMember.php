<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityMember extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','position'];

    public function activity()
    {
        return $this->belongsTo(Activity::class,'activity_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
