<?php

namespace Tests\Feature;

use App\models\Article;
use App\models\Course;
use App\models\Video;
use App\User;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    public function testCanStoreVideoComments()
    {
        $video = factory(Video::class)->create();

        $user = factory(User::class)->create();
        $data = [
            'text' => 'text',
            'active' => 0,
            'commentable_id' => $video->id,
            'user_id' => $user->id,
            'type' => 'video'
        ];
        $this->actingAs($user);
        $this->post(route('comments.store'), $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'commentable_type' => 'App\models\Video',
            'commentable_id' =>$video->id,
            'text' => 'text',
            'user_id' => $user->id
        ]);

    }

    public function testCanStoreCourseComments()
    {

        $course = factory(Course::class)->create();

        $user = factory(User::class)->create();
        $data = [
            'text' => 'text',
            'active' => 0,
            'commentable_id' => $course->id,
            'user_id' => $user->id,
            'type' => 'course'
        ];
        $this->actingAs($user);
        $this->post(route('comments.store'), $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'commentable_type' => 'App\models\course',
            'commentable_id' =>$course->id,
            'text' => 'text',
            'user_id' => $user->id
        ]);

    }

    public function testCanStoreArticleComments()
    {

        $article = factory(Article::class)->create();

        $user = factory(User::class)->create();
        $data = [
            'text' => 'text',
            'active' => 0,
            'commentable_id' => $article->id,
            'user_id' => $user->id,
            'type' => 'article'
        ];
        $this->actingAs($user);
        $this->post(route('comments.store'), $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'commentable_type' => 'App\models\Article',
            'commentable_id' =>$article->id,
            'text' => 'text',
            'user_id' => $user->id
        ]);

    }

    public function testCanDeleteComment()
    {

        //TODO write that test can delete a comment
    }

    public function testCanDeleteMultiComment()
    {
        //TODO TEST CAN DELETE MULTI
    }

    public function testCanUpdateComment()
    {
        //TODO  WRITE THAT TEST CAN UPDATE A COMMENT
    }

    public function testCanForceDeleteAComment()
    {
        //TODO WRITE THAT TEST CAN FORCE DELETE A COMMENT
    }

    public function testCanRestoreAComment()
    {
        //TODO WRITE THAT CAN RESTORE A SOFT DELETED COMMENT
    }


}
