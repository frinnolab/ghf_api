<?php

namespace App\Models\Donations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorType extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $primaryKey = 'donor_type_id';

    protected $fillable = [
        "title",
        "type",
    ];

    //["Local", 0], ["Foreign", 1], 

}
