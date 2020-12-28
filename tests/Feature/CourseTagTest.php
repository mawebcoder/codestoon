<?php

namespace Tests\Feature;

use Tests\TestCase;

class CourseTagTest extends TestCase
{
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
}
