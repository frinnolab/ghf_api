<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publication extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'publish_id';

    protected $fillable = [
        'title',
        'description',
        'publish_type',
        'publish_date',
        'author_id',
    ];
}
