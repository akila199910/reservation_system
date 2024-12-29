<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntakeForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'business_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'email',
        'contact',
        'address',
        'reason',
        'appointment_date',
        'appointment_time',
        'description',
        'communication_mode'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
