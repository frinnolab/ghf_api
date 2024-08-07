<?php

namespace App\Models\Teams;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primary_key = 'team_m_id';

    protected $fillable = [
        "team_id",
        "member_id",
        "team_position",
    ];
}
