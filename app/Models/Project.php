<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'title',
        'description',
        'video_demo',
        'status',
        'coordinator_id',
        'StarRating',
        'department_id',
        'Year',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(Account::class, 'coordinator_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProjectReview::class);
    }

    public function uploads()
    {
        return $this->hasMany(ProjectUpload::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }

    public function projfiles()
    {
        return $this->hasMany(Projfile::class);
    }
}
