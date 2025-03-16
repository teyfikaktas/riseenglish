<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseLevel;
use Illuminate\Support\Facades\Validator;

class CourseLevelController extends Controller
{
    /**
     * Display a listing of the course levels.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courseLevels = CourseLevel::orderBy('name')->paginate(10);
        return view('admin.course-levels.index', compact('courseLevels'));
    }

    /**
     * Show the form for creating a new course level.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.course-levels.create');
    }

    /**
     * Store a newly created course level in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:course_levels',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseLevel = new CourseLevel();
        $courseLevel->name = $request->name;
        $courseLevel->description = $request->description;
        $courseLevel->is_active = $request->has('is_active') ? 1 : 0;
        $courseLevel->save();

        return redirect()->route('admin.course-levels.index')
            ->with('success', 'Kurs seviyesi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified course level.
     *
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Http\Response
     */
    public function show(CourseLevel $courseLevel)
    {
        return redirect()->route('admin.course-levels.edit', $courseLevel);
    }

    /**
     * Show the form for editing the specified course level.
     *
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseLevel $courseLevel)
    {
        return view('admin.course-levels.edit', compact('courseLevel'));
    }

    /**
     * Update the specified course level in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseLevel $courseLevel)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:course_levels,name,' . $courseLevel->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseLevel->name = $request->name;
        $courseLevel->description = $request->description;
        $courseLevel->is_active = $request->has('is_active') ? 1 : 0;
        $courseLevel->save();

        return redirect()->route('admin.course-levels.index')
            ->with('success', 'Kurs seviyesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified course level from storage.
     *
     * @param  \App\Models\CourseLevel  $courseLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseLevel $courseLevel)
    {
        try {
            $courseLevel->delete();
            return redirect()->route('admin.course-levels.index')
                ->with('success', 'Kurs seviyesi başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.course-levels.index')
                ->with('error', 'Kurs seviyesi silinirken bir hata oluştu. Bu kayıt kullanımda olabilir.');
        }
    }
    public function getList()
{
    $levels = CourseLevel::select('id', 'name')->orderBy('name')->get();
    return response()->json($levels);
}
}