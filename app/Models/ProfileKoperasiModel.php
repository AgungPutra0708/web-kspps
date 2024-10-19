<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileKoperasiModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "profile_koperasis";

    protected $guarded = [];
}
