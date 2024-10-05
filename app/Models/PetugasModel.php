<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetugasModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "petugas";

    protected $guarded = [];

    public function dataUserPetugas()
    {
        return $this->belongsTo(UserMemberModel::class, 'id', 'id_user')
            ->where('status', 'petugas');
    }
}
