<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'class_id',
        'teacher_id',
        'subject_group_id'
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'grades', 'subject_id', 'student_id')
                    ->withPivot('grade')
                    ->withTimestamps();
    }

    /**
     * Get the subject group that owns the subject.
     */
    public function group()
    {
        return $this->belongsTo(SubjectGroup::class, 'subject_group_id');
    }
}
