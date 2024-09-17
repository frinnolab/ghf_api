<?php

namespace App\Models\Donations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonorCurrencyType extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'donor_currency_id';

    protected $fillable = [
        "title",
        "short_name",
        "type",
    ];
}
