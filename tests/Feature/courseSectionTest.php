<?php

namespace Tests\Feature;

use App\models\Course;
use Tests\TestCase;

class courseSectionTest extends TestCase
{
    public function testCanStoreCourseSection()
    {
        $course_id = factory(Course::class)->create()->id;
        $data = [
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'course_id' => $course_id
        ];
        $this->post(route('course.section.store'), $data)
            ->assertStatus(201)
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('course_sections', $data);
    }
}
