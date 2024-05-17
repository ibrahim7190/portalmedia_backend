<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projfile extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'projfile_path',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
