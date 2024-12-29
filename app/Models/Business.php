<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ref_no',
        'name',
        'email',
        'contact',
        'address',
        'status',
        'snap_auth_key',
        'ibson_business',
        'ibson_id',
    ];

    public function BusinessUsers(){
        return $this->hasMany(BusinessUsers::class, 'business_id','id');
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class, 'business_id','id');
    }

    public function client(){
        return $this->hasMany(Client::class, 'business_id','id');
    }
    public function intake(){
        return $this->hasMany(IntakeForm::class,'business_id','id');
    }
}
