<?php

namespace Tests\Feature;

use App\models\CourseTag;
use Tests\TestCase;

class CourseTagTest extends TestCase
{
    public $empty_success_message = ['message' => 'success', 'data' => null];

    public function testCanStoreCourseTag()
    {
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title'
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
}
