<?php

namespace App\Models\Impacts;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImpactAsset extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'impact_asset_id';

    protected $fillable = [
        'impact_id',
        'asset_url'
    ];
}
