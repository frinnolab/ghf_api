<?php

namespace App\Models\Profiles;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderAsset extends Model
{
    use HasFactory, HasUuids, SoftDeletes, Prunable;

    protected $primaryKey =  'header_asset_id';

    protected $fillable = [
        'asset_url',
        'asset_section_type'
    ];
}
