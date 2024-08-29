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
        'company_Name',
        'company_Address',
        'company_Email',
        'company_Mobile',
        'company_Mobile_Telephone',
        'company_Mobile_Altenate',
        'company_Biography',
        'company_Mission',
        'company_Vision',
        'logo_Url',
        'intro_VideoUrl',
    ];
}
