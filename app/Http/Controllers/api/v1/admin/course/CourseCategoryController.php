<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $failed_message = ['message' => 'failed', 'data' => null];

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
        $data = $request->only(
            [
                'fa_title',
                'meta',
                'description',
                'short_description',
            ]
        );
        $data['parent'] = $request->parent ?? 0;
        $data['short_description'] = $request->description ?? null;
        $data['en_title'] = $request->en_title ?? null;

        $article_category = CourseCategory::create($data);
        if ($request->hasFile('file')) {
            $path = 'images/courses/categories/cover/' . $article_category->id;
            $image_name = $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs($path, $image_name, 'public');
            $article_category->update([
                'course_image_cover_name' => $image_name
            ]);
        }
        return $article_category ?
            response($this->empty_success_message, 201) :
            response($this->failed_message);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\models\CourseCategory $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\CourseCategory $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\CourseCategory $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseCategory $courseCategory)
    {
        $data = $request->only(
            [
                'fa_title',
                'meta',
                'description',
                'short_description',
            ]
        );
        $data['parent'] = $request->parent ?? 0;
        $data['short_description'] = $request->description ?? null;
        $data['en_title'] = $request->en_title ?? null;
        $result = $courseCategory->update($data);
        if ($request->hasFile('file')) {
            $path = 'images/courses/categories/cover/' . $courseCategory->id;
            $image_name = $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs($path, $image_name, 'public');
            $courseCategory->update([
                'course_image_cover_name' => $image_name
            ]);
        }
        return $result ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\CourseCategory $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseCategory $courseCategory)
    {
        //
    }
}
