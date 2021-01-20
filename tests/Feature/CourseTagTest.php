<?php

namespace Tests\Feature;

use App\models\CourseTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTagTest extends TestCase
{
    use RefreshDatabase;

    public $empty_success_message = ['message' => 'success', 'data' => null];

    public function testCanStoreCourseTag()
    {
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'status'=>1
        ];
        $this->post(route('course.tag.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('course_tags', $data);
    }

    public function testCanUpdateCourseTag()
    {
        $courseTag = factory(CourseTag::class)->create();
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title'
        ];
        $this->put(route('course.tag.update', ['courseTag' => $courseTag->id]), $data)
            ->assertOk()
            ->assertJson($this->empty_success_message);
        $this->assertDatabaseHas('course_tags', $data);
    }

    public function testCanDeleteCourseTag()
    {
        $course_tag = factory(CourseTag::class)->create();

        $this->delete(route('course.tag.destroy', ['courseTag' => $course_tag->id]))
            ->assertOk()
            ->assertJson($this->empty_success_message);
        $this->assertDatabaseMissing('course_tags', [
            'fa_title' => $course_tag->fa_title,
            'en_title' => $course_tag->en_title
        ]);
    }

    public function testCanForceDeleteCourseTag()
    {
        //TODO TEST CAN FORCE DELETE COURSE TAG
    }

    public function testCanRestoreCourseTag()
    {
        //TODO TEST CAN RESTORE COURSE TAG
    }
}
