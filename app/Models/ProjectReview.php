<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectReview extends Model
{
    protected $primaryKey = 'review_id';
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'account_id',
        'rating',
        'comment',
        'review_date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    
}
