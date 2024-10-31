<?php

namespace App\Models\Careers;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerValidity extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'career_valid_id';

    protected $fillable = [
        'title',
        'type'
    ];

    //Closed = 0, Open = 1;
    
    
}
