<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasTimestamps;

    protected $primaryKey = 'blog_id';

    protected $fillable = [
        'title',
        'thumbnail_url',
        'description',
        'author_id',
        'is_archived',
    ];

}
