<?php

namespace App\Models\Careers;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerStatus extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'career_status_id';

    protected $fillable = [
        'title',
        'type'
    ];

    //Pending = 0 | Denied = 1 | Accepted = 2
}
