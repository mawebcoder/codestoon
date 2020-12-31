<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CourseTest extends TestCase
{

    use RefreshDatabase;

    public function testCanStoreNewCourses()
    {
        $file = UploadedFile::fake()->image('image.jpg');
        $user = factory(User::class)->create();
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
            'short_description' => 'short_description'
        ];
        $this->post(route('courses.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('courses', [
            'fa_title'=>'fa_title',
            'meta'=>'meta',
            'description'=>'description'

        ]);
        $this->assertFileExists(storage_path('app/public/images/courses/covers/1/image.jpg'));
    }
}
