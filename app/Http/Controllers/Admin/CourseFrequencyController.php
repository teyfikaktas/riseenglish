<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseFrequency;
use Illuminate\Support\Facades\Validator;

class CourseFrequencyController extends Controller
{
    /**
     * Display a listing of the course frequencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courseFrequencies = CourseFrequency::orderBy('name')->paginate(10);
        return view('admin.course-frequencies.index', compact('courseFrequencies'));
    }

    /**
     * Show the form for creating a new course frequency.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.course-frequencies.create');
    }

    /**
     * Store a newly created course frequency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:course_frequencies',
            'description' => 'nullable|string',
            'sessions_per_week' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseFrequency = new CourseFrequency();
        $courseFrequency->name = $request->name;
        $courseFrequency->description = $request->description;
        $courseFrequency->sessions_per_week = $request->sessions_per_week;
        $courseFrequency->is_active = $request->has('is_active') ? 1 : 0;
        $courseFrequency->save();

        return redirect()->route('admin.course-frequencies.index')
            ->with('success', 'Kurs frekansı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified course frequency.
     *
     * @param  \App\Models\CourseFrequency  $frequency
     * @return \Illuminate\Http\Response
     */
    public function show(CourseFrequency $courseFrequency)
    {
        return redirect()->route('admin.course-frequencies.edit', $courseFrequency);
    }

    /**
     * Show the form for editing the specified course frequency.
     *
     * @param  \App\Models\CourseFrequency  $frequency
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseFrequency $courseFrequency)
    {
        return view('admin.course-frequencies.edit', ['frequency' => $courseFrequency]);
    }

    /**
     * Update the specified course frequency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseFrequency  $frequency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseFrequency $courseFrequency)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:course_frequencies,name,' . $courseFrequency->id,
            'description' => 'nullable|string',
            'sessions_per_week' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseFrequency->name = $request->name;
        $courseFrequency->description = $request->description;
        $courseFrequency->sessions_per_week = $request->sessions_per_week;
        $courseFrequency->is_active = $request->has('is_active') ? 1 : 0;
        $courseFrequency->save();

        return redirect()->route('admin.course-frequencies.index')
            ->with('success', 'Kurs frekansı başarıyla güncellendi.');
    }

    /**
     * Remove the specified course frequency from storage.
     *
     * @param  \App\Models\CourseFrequency  $frequency
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseFrequency $courseFrequency)
    {
        try {
            $courseFrequency->delete();
            return redirect()->route('admin.course-frequencies.index')
                ->with('success', 'Kurs frekansı başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.course-frequencies.index')
                ->with('error', 'Kurs frekansı silinirken bir hata oluştu. Bu kayıt kullanımda olabilir.');
        }
    }
}