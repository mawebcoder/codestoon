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
            'course_id' => $course_id,
            'status' => 1
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

    public function testCanUpdateCourseSection()
    {
        $course_section = factory(CourseSection::class)->create();
        $course = factory(Course::class)->create();
        $data = [
            'course_id' => $course->id,
            'fa_title' => 'fa_title',
            'en_title' => 'en_title',
            'status' => 1
        ];
        $this->put(route('course.section.update', ['courseSection' => $course_section->id]), $data)
            ->assertOk()
            ->assertJson([
                'message' => 'success',
                'data' => null
            ]);
        $this->assertDatabaseHas('course_sections', $data);
    }

    public function testCanDeleteMultipleCourseSection()
    {
        $course_sections_id = factory(CourseSection::class, 10)->create()
            ->pluck('id')->toArray();

        $this->json('post', route('course.section.multi'), ['ids' => $course_sections_id])
            ->assertOk();

        foreach ($course_sections_id as $id) {
            $this->assertSoftDeleted('course_sections', [
                'id' => $id
            ]);
        }


    }

    public function testCanForceDeleteCourseSection()
    {
        //TODO TEST CAN FORCE DELETE COURSE SECTION
    }

    public function testCanRestoreCourseSection()
    {
        //TODO TEST CAN RESTORE COURSE SECTION
    }

}
