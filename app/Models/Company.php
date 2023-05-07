<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public function reshape()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'logo' => url('/public/logos') . '/' . $this->logo_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pos_size' => count($this->positions)
        ];
    }
    protected $fillable = [
        'user_id', 'name', 'logo_url', 'description'
    ];
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function positions()
    {
        return $this->hasMany(CompanyPositions::class);
    }
}
