<?php

namespace Tests\Feature;

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
        $data['course_image_cover_name'] = 'image.jpg';
        $this->assertDatabaseHas('course_categories', [
            'meta' => 'meta',
            'fa_title' => 'fa_title',
            'en_title' => 'en_title'
        ]);
        $this->assertFileExists(storage_path('app/public/images/courses/categories/cover/1/image.jpg'));
    }
}
