<?php

namespace App\Models\Partners;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerType extends Model
{
    use HasFactory;

    use HasFactory, HasUuids,  SoftDeletes;

    protected $primaryKey = 'partner_type_id';

    protected $fillable = [
        'name',
        'type',
    ];
}
