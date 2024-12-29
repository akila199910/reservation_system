<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'gender',
        'dob',
        'street_no',
        'street_address',
        'city'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
