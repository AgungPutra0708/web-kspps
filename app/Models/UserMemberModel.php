<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserMemberModel extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = "user_anggotas";

    protected $guarded = [];
}
