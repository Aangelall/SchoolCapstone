<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $fillable = [
        'class_id',
        'student_id',
        'is_promoted',
        'adviser_name'  // Make sure this is included
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
