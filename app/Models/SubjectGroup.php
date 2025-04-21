<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level',
        'strand',
        'semester'
    ];

    /**
     * Get the subjects that belong to this group.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subject_group_id');
    }
} 