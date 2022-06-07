<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPositions extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'location',
        'job_role',
        'postion_name',
        'job_level',
        'experience',
        'salary',
        'remote',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function applications()
    {
        return $this->hasMany(CompanyApplications::class);
    }
    protected $casts = [
        'remote' => 'boolean'
    ];
}
