<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'leave_type',
        'date_from',
        'date_to',
        'days',
        'reason',
        'is_approved'
    ];

    protected $dates = ['date_from', 'date_to'];

    const TYPE_DAY_OFF = 'Day off',
        TYPE_SICK_DAY = 'Sick day',
        TYPE_VACATION = 'Vacation';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->days = $model->date_to->diffInDays($model->date_from);
            if ($model->days == 0)
                $model->days = 1;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            // ... code here
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'employee_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
