<?php

namespace Tests\Feature;

use App\models\Course;
use App\models\CourseSection;
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

    public function testCanDeleteCourseSection()
    {
        $course_section = factory(CourseSection::class)->create();
        $this->delete(route('course.section.destroy', ['courseSection' => $course_section->id]))
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertSoftDeleted('course_sections', [
            'id' => $course_section->id,
            'fa_title' => $course_section->fa_title
        ]);
    }
}
