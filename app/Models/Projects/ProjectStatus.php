<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStatus extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'status_id';

    protected $fillable = [
        'name',
        'type',
    ];
}
