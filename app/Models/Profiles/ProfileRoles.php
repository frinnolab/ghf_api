<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileRoles extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    #ROle Types
    protected $primaryKey = 'profile_role_id';

    protected $fillable = [
        'name',
        'type'
    ];
}
