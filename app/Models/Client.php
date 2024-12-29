<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ref_no',
        'first_name',
        'last_name',
        'name',
        'email',
        'contact',
        'status',
        'business_id'
    ];

    public function client_profile()
    {
        return $this->hasOne(ClientProfile::class,'client_id', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
