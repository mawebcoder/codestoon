<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
        //TODO SHOW ALL COURSECATEGORIES IN PAGINATION
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    //TODO VALIDATION STORING THE COURSE CATEGORY IN THE SYSTEM
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
    //TODO VALIDATION OF THE UPDATE COURSE CATEGORY
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
        $result = $courseCategory->delete();
        return $result ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }

    //TODO VALIDATION OF THE FORCE DELETE OF THE COURSE CATETORY
    public function forceDelete()
    {
        $ids = request()->ids;
        $result = CourseCategory::onlyTrashed()->whereIn('id', $ids)->forceDelete();
        foreach ($ids as $id) {
            $path = storage_path('app/public/images/courses/categories/cover/' . $id);
            if (is_dir($path)) {
                File::deleteDirectory($path);
            }
        }
        return $result ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }

    public function getTrashed()
    {
        $all_trashed = CourseCategory::onlyTrashed()->select('fa_title', 'en_title', 'meta', 'id')
            ->get();
        return response([
            'message' => 'success',
            'data' => $all_trashed
        ]);
    }

    //TODO VALIDATION OF THE COURSE CATEGORY RESTORING
    public function restoreCourseCategory()
    {
        //TODO RESTORE COURSE CATEGORY RESTORE
    }

    //TODO VALIDATION OF THE DELETE MULTIPLE COURSE CATEGORY
    public function deleteMultiple()
    {
        //TODO DELETE MULTIPLE COURSE CATEGORY
    }
}
