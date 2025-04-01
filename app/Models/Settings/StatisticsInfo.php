<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatisticsInfo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'stat_id';

    protected $fillable = [
        'regions_reached',
        'districts_reached',
        'students_impacted',
        'schools_reached',
    ];
}
