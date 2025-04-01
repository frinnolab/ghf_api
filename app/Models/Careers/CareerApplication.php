<?php

namespace App\Models\Careers;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerApplication extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'career_app_id';

    protected $fillable = [
        'career_id',
        'avatar_url',
        'cv_url',
        'email',
        'firstname',
        'lastname',
        'mobile',
        'biography',
        'city',
        'country',
        'career_status',//Pending, Declined, Accepted
        'career_role_type',//Employee = 3 | Volunteer = 4
    ];
}
