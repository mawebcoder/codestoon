<?php

namespace Tests\Feature;

use App\models\Course;
use App\models\CourseCategory;
use App\models\CourseTag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CourseTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function testCanStoreNewCourses()
    {
        $file = UploadedFile::fake()->image('image.jpg');
        $user = factory(User::class)->create();
        $course_tags_ids = factory(CourseTag::class, 4)->create()->pluck('id')
            ->toArray();
        $course_category_id = factory(CourseCategory::class)->create()->id;
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'price' => 20000,
            'has_discount' => 1,
            'discount_value' => 10,
            'level' => 'beginner',
            'user_id' => $user->id,
            'file' => $file,
            'is_special_subscription' => 0,
            'description' => 'description',
            'meta' => 'meta',
            'tag_ids' => $course_tags_ids,
            'courseCategory_id' => $course_category_id,
            'short_description' => 'short_description',
        ];
        $this->post(route('courses.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('courses', [
            'fa_title' => 'fa_title',
            'meta' => 'meta',
            'description' => 'description',
            'course_image_cover' => $file->getClientOriginalName(),
            'courseCategory_id' => $course_category_id
        ]);
        $this->assertDatabaseHas('category_course', [
            'course_id' => 1,
            'courseCategory_id' => $course_category_id
        ]);
        $this->assertDatabaseHas('course_tag', [
            'courseTag_id' => $course_tags_ids[0],
            'course_id' => 1
        ]);

        $this->assertFileExists(storage_path('app/public/images/courses/covers/1/image.jpg'));
    }

    /**
     * @test
     */
    public function testCanUpdateCourse()
    {
        $file = UploadedFile::fake()->image('new_image.jpg');
        $course_tags_ids = factory(CourseTag::class, 4)->create()->pluck('id')
            ->toArray();
        $course_category_id = factory(CourseCategory::class)->create()->id;
        $course = factory(Course::class)->create();
        $user = factory(User::class)->create();
        $data = [
            'en_title' => 'new_en_title',
            'fa_title' => 'new_fa_title',
            'description' => 'new_description',
            'price' => 10,
            'meta' => 'new_meta',
            'has_discount' => 1,
            'file' => $file,
            'is_active' => 1,
            'discount_value' => 20,
            'level' => 'advanced',
            'courseCategory_id' => $course_category_id,
            'user_id' => $user->id,
            'tag_ids' => $course_tags_ids,
            'is_special_subscription' => 1,
            'short_description' => 'new_short_description',
            'is_completed_course' => 1
        ];
        $this->put(route('courses.update', ['course' => $course->id]), $data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('courses', [
            'en_title' => 'new_en_title',
            'fa_title' => 'new_fa_title',
            'description' => 'new_description',
            'price' => 10,
            'meta' => 'new_meta',
            'has_discount' => 1,
            'is_active' => 1,
            'course_image_cover' => $file->getClientOriginalName(),
            'discount_value' => 20,
            'level' => 'advanced',
            'user_id' => $user->id,
            'is_special_subscription' => 1,
            'short_description' => 'new_short_description',
            'is_completed_course' => 1
        ]);
        $this->assertDatabaseHas('category_course', [
            'course_id' => 1,
            'courseCategory_id' => $course_category_id
        ]);
        $this->assertDatabaseHas('course_tag', [
            'courseTag_id' => $course_tags_ids[0],
            'course_id' => 1
        ]);
        $this->assertFileExists(storage_path('app/public/images/courses/covers/' . $course->id . '/new_image.jpg'));
    }

    public function testCanDeleteCourse()
    {
        $course = factory(Course::class)->create();

        $this->delete(route('courses.destroy', ['course' => $course->id]))
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('courses', [
            'id' => $course->id,
            'fa_title' => $course->fa_title
        ]);
        $this->assertFileDoesNotExist(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover));
    }

    public function testCanForceDeleteCourse()
    {
        //TODO TEST CAN FORCE DELETE COURSE
    }

    public function testCanRestoreCourse()
    {
        //TODO TEST CAN RESTORE DELETE COURSE
    }


}
