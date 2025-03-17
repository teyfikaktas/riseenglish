<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
// app/Models/Announcement.php
protected $fillable = ['course_id', 'title', 'content'];

public function course()
{
    return $this->belongsTo(Course::class);

}}
