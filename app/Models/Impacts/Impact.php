<?php

namespace App\Models\Impacts;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Impact extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'impact_id';

    protected $fillable = [
        "title",
        "description",
        "school_name",
        "school_region",
        "school_district",
        "school_reached_total",
        "student_boys",
        "student_girls",
        "student_total",
    ];
}
