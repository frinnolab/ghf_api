<?php

namespace App\Models\Careers;

use Illuminate\Database\Eloquent\Concerns\HasUniqueIds;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerType extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'career_type_id';

    protected $fillable = [
        'title',
        'type',
    ];
}
