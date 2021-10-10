<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator as VendorAdministrator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends VendorAdministrator
{

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }
}
