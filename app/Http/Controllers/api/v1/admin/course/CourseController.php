<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\models\Course;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
