<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, HasUuids,  SoftDeletes;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'name',
        'description',
        'regions_reached',
        'districts_reached',
        'schools_reached',
        'students_reached',
        'status',
        'date_start',
        'date_end',
        'publisher_profile_id',
        'programme_id',
    ];
}
