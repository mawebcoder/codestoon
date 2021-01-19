<?php

namespace Tests\Feature;

use App\models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class CourseCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCanStoreNewCourseCategory()
    {
        UploadedFile::fake();

        $file = UploadedFile::fake()->image('image.jpg');

        $data = [
            'meta' => 'meta',
            'description' => Str::random(400),
            'short_description' => Str::random(50),
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'parent' => 0,
            'file' => $file
        ];
        $this->post(route('courses.category.store'), $data)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ], 201);

        $this->assertDatabaseHas('course_categories', [
            'meta' => 'meta',
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'cover_file_name'=>$file->getClientOriginalName()
        ]);
        $this->assertFileExists(storage_path('app/public/images/courses/categories/cover/1/image.jpg'));
    }

    public function testCanUpdateCourseCategory()
    {
        UploadedFile::fake();
        $file = UploadedFile::fake()->image('image.jpg');
        $old_course_category = factory(CourseCategory::class)->create();
        $new_data = [
            'meta' => 'meta',
            'en_title' => 'en_title',
            'fa_title' => 'fa_title',
            'description' => 'description',
            'short_description' => 'short_description',
            'file' => $file
        ];
        $this->put(route('courses.category.update', ['courseCategory' => $old_course_category->id]), $new_data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('course_categories', [
            'fa_title' => 'fa_title',
            'cover_file_name' => $file->getClientOriginalName(),
            'en_title' => 'en_title'
        ]);
        $this->assertFileExists(storage_path('app/public/images/courses/categories/cover/1/image.jpg'));
    }

    public function testCanDeleteCourseCategory()
    {
        $course_category = factory(CourseCategory::class)->create();
        $this->delete(route('courses.category.delete', ['courseCategory' => $course_category->id]))
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('course_categories', [
            'meta' => $course_category->meta,
            'fa_title' => $course_category->fa_title
        ]);
    }


    public function testCanForceDeleteCourseCategory()
    {
        //TODO TEST CAN FORCE DELETE COURSE CATEGORY
    }

    public function testCanRestoreCourseCategory()
    {
        //TODO TEST CAN RESTORE COURSE CATEGORY
    }
}
