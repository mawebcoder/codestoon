<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\StoreCourseValidation;
use App\models\Course;
use App\models\CourseCategory;
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
        //TODO SHOW ALL COURSES IN PAGINATION MODE
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //TODO VALIDATION OF THE STORE COURSE IN THE SYSTEM
    public function store(StoreCourseValidation $request)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'price',
            'has_discount',
            'level',
            'user_id',
            'description',
            'short_description',
            'meta',
            'courseCategory_id'
        ]);
        $data['has_discount'] = intval($request->discount_value) ? 1 : 0;
        $data['discount_value'] = intval($request->discount_value) ?? 0;
        $data['is_active'] = $request->is_active ? 1 : 0;
        $data['is_special_subscription'] = $request->is_special_subscription ? 1 : 0;
        $data['is_completed_course']=$request->is_completed_course ? 1:0;
        $course = Course::create($data);

        $this->syncCategories($course, $request);

        $this->syncTags($course, $request);

        $this->upload($course, $request);

        return $course ?
            response($this->empty_success, 201) :
            response($this->failed, 500);

    }

    public function syncCategories($course, $request)
    {
        $get_all_categories_parent = CourseCategory::getAllParentsIds($request->courseCategory_id);
        $course->courseCategories()->sync($get_all_categories_parent);
    }

    public function syncTags($course, $request)
    {
        if ($request->has('tag_ids')) {
            $course->tags()->sync($request->tag_ids);
        }
    }

    public function upload($course, $request)
    {
        if ($request->hasFile('file')) {
            $path = 'images/courses/covers/' . $course->id;
            $file_name = $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs($path, $file_name, 'public');
            $course->update([
                'course_image_cover' => $file_name
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //TODO SHOW INFO OF THE COURSE
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */

    //TODO VALIDATION OF THE UPDATE COURSE
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
            'courseCategory_id',
            'is_special_subscription',
            'is_completed_course',
            'courseCategory_id'
        ]);
        $result = $course->update($data);
        $get_all_categories_parent = CourseCategory::getAllParents($request->courseCategory_id);
        $course->courseCategories()->sync($get_all_categories_parent);
        $course->tags()->sync($request->tag_ids);

        //TODO REFACTOR THIS UPLOADING FILE IN ANOTHER METHOD
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
        return $result ?
            response($this->empty_success, 200) :
            response($this->failed, 500);
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

    //TODO VALIDATION OF THE FORCE IN COURSE CONTROLLER
    public function forceDelete()
    {
        //TODO FORCE DELETE OF THE COURSE
    }

    //TODO VALIDATION OF THE RESTORE COURSES
    public function restore()
    {
        //TODO RESTORING THE COURSES IN COURSE CONTROLLER
    }

    public function getTrashed()
    {
        //TODO GET TRASHED COURSES
    }

    //TODO VALIDATION OF THE DELETE MULTIPLE COURSE
    public function deleteMultiple()
    {
        //TODO DELETE MULTIPLE COURSE
    }
}
