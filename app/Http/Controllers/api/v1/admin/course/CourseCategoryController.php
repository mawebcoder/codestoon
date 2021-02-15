<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\category\ForceDeleteCourseCategoryValidation;
use App\Http\Requests\courses\category\StoreCourseCategoryValidation;
use App\Http\Requests\courses\category\UpdateCourseCategoryValidation;
use App\models\CourseCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CourseCategoryController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $failed_message = ['message' => 'failed', 'data' => null];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct()
    {
        //TODO SET PERMISSIONS OF THE COURSE CATEGORIES
    }

    public function index()
    {
        if (!request()->has('search')) {
            if (request()->has('select_box')) {
                $course_categories = CourseCategory::query()
                    ->select('id', 'fa_title')
                    ->get();
            } else {
                $course_categories = CourseCategory::select(
                    'id',
                    'fa_title',
                    'en_title',
                    'short_description',
                    'status',
                    'description',
                    'cover_file_name',
                    'parent',
                    'meta'
                )->with('father:id,fa_title')
                    ->paginate(30);
            }
        } else {
            $course_categories = CourseCategory::select(
                'id',
                'fa_title',
                'en_title',
                'short_description',
                'status',
                'description',
                'cover_file_name',
                'parent',
                'meta'
            )->with('father:id,fa_title')
                ->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhere('en_title', 'like', '%' . request()->search . '%')
                ->get();
        }

        return $course_categories->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_categories]) :
            response($this->empty_success_message, 204);
    }

    public function getActiveCourseCategory()
    {
        if (!request()->has('search')) {
            $course_categories = CourseCategory::select(
                'id',
                'fa_title',
                'en_title',
                'short_description',
                'status',
                'description',
                'cover_file_name',
                'parent',
                'meta'
            )->whereStatus(1)
                ->with('father:id,fa_title')
                ->paginate(30);
        } else {
            $course_categories = CourseCategory::select(
                'id',
                'fa_title',
                'en_title',
                'short_description',
                'status',
                'description',
                'cover_file_name',
                'parent',
                'meta'
            )->with('father:id,fa_title')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->whereStatus(1)
                ->get();
        }

        return $course_categories->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_categories]) :
            response($this->empty_success_message, 204);

    }

    public function getDeActiveCourseCategory()
    {
        if (!request()->has('search')) {
            $course_categories = CourseCategory::select(
                'id',
                'fa_title',
                'en_title',
                'short_description',
                'status',
                'description',
                'cover_file_name',
                'parent',
                'meta'
            )->whereStatus(0)
                ->with('father:id,fa_title')
                ->paginate(30);
        } else {
            $course_categories = CourseCategory::select(
                'id',
                'fa_title',
                'en_title',
                'short_description',
                'status',
                'description',
                'cover_file_name',
                'parent',
                'meta'
            )->with('father:id,fa_title')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->whereStatus(0)
                ->get();
        }

        return $course_categories->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_categories]) :
            response($this->empty_success_message, 204);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */


    public function store(StoreCourseCategoryValidation $request)
    {
        $data = $request->only(
            [
                'fa_title',
                'meta',
                'description',
            ]
        );
        $data['parent'] = $request->parent ?? 0;
        $data['short_description'] = $request->description ?? null;
        $data['en_title'] = $request->en_title ?? null;
        $data['status'] = $request->status ? 1 : 0;


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
     * @param CourseCategory $courseCategory
     * @return Response
     */
    public function edit(CourseCategory $courseCategory)
    {

        return response([
            'message' => 'success',
            'data' => [
                'category' => $courseCategory,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCourseCategoryValidation $request
     * @param CourseCategory $courseCategory
     * @return Response
     */

    public function update(UpdateCourseCategoryValidation $request, CourseCategory $courseCategory)
    {
        $data = $request->only(
            [
                'fa_title',
                'meta',
                'description',
            ]
        );
        $data['parent'] = $request->parent ?? 0;
        $data['status'] = $request->status ? 1 : 0;
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
     * @param CourseCategory $courseCategory
     * @return Response
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

        if (!request()->has('search')){
            $all_trashed = CourseCategory::onlyTrashed()->select
            (
                'id',
                'fa_title',
                'en_title')->paginate(1);
        }else{
            $all_trashed = CourseCategory::onlyTrashed()->select
            (
                'id',
                'fa_title',
                'en_title')
                ->where('fa_title','like','%'.request()->search.'%')
                ->orWhere('en_title','like','%'.request()->search.'%')
                ->get();
        }

        return $all_trashed->isNotEmpty()?
            response([
            'message' => 'success',
            'data' => $all_trashed
        ]):
            response(['message'=>'success','data'=>null],204);
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
