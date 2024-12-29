<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableType extends Model
{
    use HasFactory, SoftDeletes;

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function elements_list()
    {
        return $this->hasMany(TableLayout::class, 'type_id', 'id');
    }
}
