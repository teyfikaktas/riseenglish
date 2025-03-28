<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\User;

class BulkSms extends Component
{
    public $targetGroup = 'all_users';
    public $courseSearch = '';
    public $courseResults = [];
    public $selectedCourse = null;
    public $selectedCourseId = null;
    public $message = '';
    public $charCount = 0;

    protected $rules = [
        'message' => 'required|max:160',
        'targetGroup' => 'required|in:all_users,all_students,course_students',
    ];

    public function updatedTargetGroup()
    {
        if ($this->targetGroup !== 'course_students') {
            $this->selectedCourse = null;
            $this->selectedCourseId = null;
            $this->courseSearch = '';
            $this->courseResults = [];
        }
    }

    public function updatedCourseSearch()
    {
        // En az 3 karakter girildiğinde aramayı başlat
        if (strlen($this->courseSearch) >= 3) {
            $this->courseResults = Course::where('name', 'like', '%' . $this->courseSearch . '%')
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->courseResults = [];
        }
    }

    public function updatedMessage()
    {
        $this->charCount = strlen($this->message);
    }

    public function selectCourse($courseId)
    {
        $course = Course::find($courseId);
        if ($course) {
            $this->selectedCourse = $course;
            $this->selectedCourseId = $course->id;
            $this->courseSearch = $course->name;
            $this->courseResults = [];
        }
    }

    public function clearCourseSelection()
    {
        $this->selectedCourse = null;
        $this->selectedCourseId = null;
        $this->courseSearch = '';
    }

    public function sendBulkSms()
    {
        $this->validate();
        
        if ($this->targetGroup === 'course_students' && !$this->selectedCourseId) {
            $this->addError('selectedCourseId', 'Lütfen bir kurs seçin.');
            return;
        }

        // Hedef kitleyi belirle
        $users = [];
        switch ($this->targetGroup) {
            case 'all_users':
                $users = User::all();
                break;
            case 'all_students':
                $users = User::where('role', 'student')->get();
                break;
            case 'course_students':
                $users = $this->selectedCourse->students;
                break;
        }

        $count = count($users);
        
        // Burada SMS gönderme işlemi yapılacak
        // foreach ($users as $user) {
        //     SmsService::send($user->phone, $this->message);
        // }
        
        // Başarı mesajı
        session()->flash('message', "{$count} kullanıcıya SMS başarıyla gönderildi!");
        
        // Formu temizle
        $this->reset(['message', 'selectedCourse', 'selectedCourseId', 'courseSearch', 'charCount']);
        $this->targetGroup = 'all_users';
    }

    public function render()
    {
        return view('livewire.bulk-sms');
    }
}