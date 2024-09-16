<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicationAsset extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    
    protected $primaryKey = 'publish_asset_id';

    protected $fillable = [
        'title',
        'publish_id',
        'asset_url',
    ];

}
