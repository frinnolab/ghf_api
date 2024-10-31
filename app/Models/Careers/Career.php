<?php

namespace App\Models\Careers;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'career_id';

    protected $fillable = [
        'position',
        'description',
        'career_type',//Emloyment,Volunteering
        'requirements',
    ];
}
