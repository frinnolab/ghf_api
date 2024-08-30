<?php

namespace App\Models\Partners;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, HasUuids,  SoftDeletes;

    protected $primaryKey = 'partner_id';

    protected $fillable = [
        'name',
        'description',
        'logo_url',
        'type',
        'start_year',
        'end_year',
    ];
}
