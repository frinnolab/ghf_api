<?php

namespace App\Models\Donations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorStatus extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'donor_status_id';

    protected $fillable = [
        "title",
        "type"
    ];
}
