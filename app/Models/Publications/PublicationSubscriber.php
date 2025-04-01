<?php

namespace App\Models\publications;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicationSubscriber extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'subscriberId';

    protected $fillable = [
        "email",
        "isSubscribed"
    ];

}
