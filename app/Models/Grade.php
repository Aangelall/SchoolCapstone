<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'grade',
        'period',
        'period_type',
        'is_confirmed',
        'teacher_name'
    ];

    protected $casts = [
        'grade' => 'integer',
        'is_confirmed' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
