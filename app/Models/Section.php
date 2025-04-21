<?php

// app/Models/Section.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'grade_level', 'strand_id'];
    
    
    // Optional: Cast grade_level to integer
    protected $casts = [
        'grade_level' => 'integer'
    ];

    public function strand()
    {
        return $this->belongsTo(Strand::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }
}
