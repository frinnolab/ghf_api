<?php

namespace App\Models\AboutInfo;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUniqueIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutInfo extends Model
{
    use HasFactory, HasUniqueIds, HasTimestamps, SoftDeletes;

    protected $primaryKey = 'about_id';

    protected $fillable = [
        'title',
        'description',
    ];
}
