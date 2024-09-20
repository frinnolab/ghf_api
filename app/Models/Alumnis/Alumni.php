<?php

namespace App\Models\Alumnis;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumni extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'alumni_id';

    protected $fillable = [
        "profile_id",
        "age",
        "participation_school",
        "participation_year",
        "currenct_occupation",
        "story",
    ];
}
