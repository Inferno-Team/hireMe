<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyApplications extends Model
{
    protected $fillable = ['user_id', 'position_id', 'cv_file'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function position(){
        return $this->belongsTo(CompanyPositions::class,'position_id');
    }
}
