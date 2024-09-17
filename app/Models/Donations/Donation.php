<?php

namespace App\Models\Donations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'donation_id';

    protected $fillable = [
        "email",
        "first_name",
        "last_name",
        "company",
        "description",
        "mobile",
        "amount_pledged",
        "donor_currency_type",
        "donor_type",
    ];


}
