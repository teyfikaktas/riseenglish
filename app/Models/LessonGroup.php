<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonGroup extends Model
{
    protected $table = 'lesson_group_students';
    
    protected $fillable = [
        'group_id',
        'student_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Belirli bir gruptaki tüm öğrencileri getir
    public static function getStudentsByGroupId($groupId)
    {
        return User::join('lesson_group_students', 'users.id', '=', 'lesson_group_students.student_id')
                   ->where('lesson_group_students.group_id', $groupId)
                   ->select('users.*')
                   ->get();
    }
}