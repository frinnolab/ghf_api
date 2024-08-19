<?php

namespace App\Models\Teams;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'team_id';

    protected $fillable = [
        "name",
        "total_members"
    ];
}
