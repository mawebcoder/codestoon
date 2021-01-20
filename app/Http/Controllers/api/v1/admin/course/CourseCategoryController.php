<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\category\ForceDeleteCourseCategoryValidation;
use App\Http\Requests\courses\category\StoreCourseCategoryValidation;
use App\Http\Requests\courses\category\UpdateCourseCategoryValidation;
use App\models\CourseCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $course_categories = CourseCategory::select(
            'id',
            'fa_title',
            'short_description',
            'status',
            'cover_file_name',
            'parent'
        )->with('father:id,fa_title')
            ->paginate(30);
        return response(['message' => 'success', 'data' => $course_categories]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function store(StoreCourseCategoryValidation $request)
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


        $course_category = CourseCategory::create($data);

        $this->upload($course_category, $request);

        return $course_category ?
            response($this->empty_success_message, 201) :
            response($this->failed_message);
    }

    public function upload($course_category, $request)
    {
        if ($request->hasFile('file')) {
            $path = 'images/courses/categories/cover/' . $course_category->id;
            if (is_dir(storage_path('app/public/images/courses/categories/cover/' . $course_category->id))) {
                Storage::disk('public')->deleteDirectory($path);
            }
            $image_name = $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs($path, $image_name, 'public');
            $course_category->update([
                'cover_file_name' => $image_name
            ]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\CourseCategory $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseCategory $courseCategory)
    {
        $courseCategories = CourseCategory::whereNotIn('id', [$courseCategory->id])->get();
        return response([
            'message' => 'success',
            'data' => [
                'category' => $courseCategory,
                'categories' => $courseCategories
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\CourseCategory $courseCategory
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateCourseCategoryValidation $request, CourseCategory $courseCategory)
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
        $this->upload($courseCategory, $request);
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


    public function forceDelete(ForceDeleteCourseCategoryValidation $request)
    {
        $ids = $request->ids;
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


    public function restore(ForceDeleteCourseCategoryValidation $request)
    {
        $soft_deleted_course_categories = CourseCategory::onlyTrashed()
            ->whereIn('id', $request->ids)->restore();
        return $soft_deleted_course_categories ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }


    public function deleteMulti(ForceDeleteCourseCategoryValidation $request)
    {
        $result = CourseCategory::whereIn('id', $request->ids)->delete();

        return $result ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }
}
