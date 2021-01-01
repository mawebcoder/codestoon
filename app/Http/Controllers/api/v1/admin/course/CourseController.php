<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CourseController extends Controller
{
    public $empty_success = ['message' => 'success', 'data' => null];
    public $failed = ['message' => 'failed', 'data' => null];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'price',
            'has_discount',
            'discount_value',
            'level',
            'user_id',
            'is_special_subscription',
            'description',
            'short_description',
            'meta'
        ]);
        $course = Course::create($data);

        if ($request->hasFile('file')) {
            $path = 'images/courses/covers/' . $course->id;
            $file_name = $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs($path, $file_name, 'public');
            $course->update([
                'course_image_cover' => $file_name
            ]);
        }

        return $course ?
            response($this->empty_success, 201) :
            response($this->failed, 500);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'short_description',
            'meta',
            'price',
            'discount_value',
            'has_discount',
            'is_active',
            'level',
            'user_id',
            'is_special_subscription',
            'is_completed_course'
        ]);
        $result = $course->update($data);
        if ($result) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = 'images/courses/covers/' . $course->id;
                $file_name = $file->getClientOriginalName();
                if (File::exists(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover))) {
                    unlink(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover));
                }
                $file->storeAs($path, $file_name, 'public');


                $course->update(
                    [
                        'course_image_cover' => $file_name
                    ]
                );
            }
            return response($this->empty_success, 200);
        }
        return response($this->failed, 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $result = $course->delete();
        if ($result) {
            if (file_exists(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover))) {
                unlink(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover));
            };
            return response($this->empty_success);
        }
        return response($this->failed, 500);

    }
}
