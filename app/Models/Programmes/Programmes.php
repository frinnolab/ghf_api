<?php

namespace App\Models\Programmes;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programmes extends Model
{
    use HasFactory,HasUuids,  SoftDeletes;

    protected $primaryKey = 'programme_id';

    protected $fillable = [
        'name',
        'description',
        'status',
        'date_start',
        'date_end',
        'publisher_profile_id',
    ];
}
