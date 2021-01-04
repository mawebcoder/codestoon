<?php

namespace Tests\Feature;

use App\models\Course;
use App\models\CourseSection;
use App\models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testCanStoreNewCourseVideo()
    {
        $course = factory(Course::class)->create();
        $courseSection = factory(CourseSection::class)->create(['course_id' => $course->id]);
        $data = [
            'en_title' => 'en_title',
            'fa_title' => 'fa_title',
            'short_description' => 'short_description',
            'description' => 'description',
            "hour" => "30",
            'min' => "40",
            'sec' => "40",
            'meta' => 'meta',
            'is_special_subscription' => 0,
            'courseSection_id' => $courseSection->id,
            'course_id' => $course->id,
            'video_url_name' => 'video url name'
        ];
        $this->post(route('videos.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('videos', [
            'en_title' => 'en_title',
            'fa_title' => 'fa_title',
            'short_description' => 'short_description',
            'description' => 'description',
            'time' => "30:40:40",
            'is_special_subscription' => 0,
            'courseSection_id' => $courseSection->id,
            'course_id' => $course->id,
        ]);
    }

    public function testCanUploadCourseVideo()
    {
        $course = factory(Course::class)->create();
        $course_section = factory(CourseSection::class)->create(['course_id' => $course->id]);
        $video = factory(Video::class)->create(['is_single_video' => 0, 'courseSection_id' => $course_section->id, 'course_id' => $course->id]);
        $file = UploadedFile::fake()->image('image.jpg');
        $data = [
            'file' => $file
        ];
        $this->post(route('videos.upload', ['video' => $video->id]), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertFileExists(storage_path('app/videos/courses/' . $course->id . '/' . $video->id . '/image.jpg'));
    }
}
