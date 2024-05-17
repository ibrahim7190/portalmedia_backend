<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'screenshot_path',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
