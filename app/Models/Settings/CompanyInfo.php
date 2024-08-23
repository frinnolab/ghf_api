<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyInfo extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'companyName',
        'companyAddress',
        'companyEmail',
        'companyMobile',
        'companyMobileTelephone',
        'companyMobileAltenate',
        'companyBiography',
        'companyMission',
        'companyVision',
        'logoUrl',
        'introVideoUrl',
    ];
}
