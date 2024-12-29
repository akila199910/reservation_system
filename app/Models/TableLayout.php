<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TableLayout extends Model
{
    use HasFactory, SoftDeletes;

    public function element_type()
    {
        return $this->hasOne(TableType::class, 'id', 'type_id');
    }

    public function created_by_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function getNormalImageAttribute($value)
    {
        return $value
            ? config('aws_url.url') . $value
            : null;
    }

    public function getCheckedinImageAttribute($value)
    {
        return $value
            ? config('aws_url.url') . $value
            : null;
    }
}
