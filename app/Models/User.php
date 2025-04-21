<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'first_name',
        'last_name',
        'lrn',
        'birthday',
        'access_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'access_enabled' => 'boolean',
        ];
    }

    /**
     * Get the subjects that the teacher is assigned to teach.
     */
    public function teacherSubjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }
    public function class()
    {
        return $this->belongsToMany(Classes::class, 'class_students', 'student_id', 'class_id')
                    ->latest()
                    ->limit(1);
    }
    /**
     * Get the grades for the student.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }
    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class, 'student_id');
    }
    /**
     * Get the subjects the student is enrolled in with their grades.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grades', 'student_id', 'subject_id')
                    ->withPivot('grade')
                    ->withTimestamps();
    }
}
