<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Profile extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasApiTokens;

    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'avatar_url',
        'firstname',
        'email',
        'hashed_password',
        'position',
        'mobile',
        'roleType',
    ];
}
