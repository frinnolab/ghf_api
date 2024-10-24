<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectAsset extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'project_asset_id';

    protected $fillable = [
        'project_id',
        'asset_url'
    ];
}
