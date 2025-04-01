<?php

namespace App\Models\Impacts;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImpactReport extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'impact_report_id';

    protected $fillable = [
        'impact_id',
        'title',
        'report_url'
    ];
}
