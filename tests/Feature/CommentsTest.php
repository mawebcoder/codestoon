<?php

namespace Tests\Feature;

use App\models\Article;
use App\models\Comment;
use App\models\Course;
use App\models\Video;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

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
            'commentable_id' => $video->id,
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
            'commentable_id' => $course->id,
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
            'commentable_id' => $article->id,
            'text' => 'text',
            'user_id' => $user->id
        ]);

    }

    public function testCanSoftDeleteComment()
    {
        $course = factory(Course::class)->create();

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $comment = factory(Comment::class)->create([
            'text' => 'text',
            'commentable_id' => $course->id,
            'commentable_type' => 'App\models\Course',
            'user_id' => $user->id,
            'parent' => 0
        ]);
        $this->delete(route('comments.destroy', ['comment' => $comment->id]))
            ->assertOk();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id
        ]);

    }

    public function testCanDeleteMultiComment()
    {
        $course = factory(Course::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $comments = factory(Comment::class,10)->create([
            'text' => 'text',
            'commentable_id' => $course->id,
            'commentable_type' => 'App\models\Course',
            'user_id' => $user->id,
            'parent' => 0
        ]);
        $ids = $comments->pluck('id')->toArray();

        $this->post(route('comments.delete.multi'), ['ids' => $ids])
            ->assertOk();

        foreach ($ids as $id) {
            $this->assertSoftDeleted('comments', [
                'id' => $id
            ]);
        }
    }


    public function testCanForceDeleteAComment()
    {
        $course = factory(Course::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $comments = factory(Comment::class,10)->create([
            'text' => 'text',
            'commentable_id' => $course->id,
            'commentable_type' => 'App\models\Course',
            'user_id' => $user->id,
            'parent' => 0
        ]);
        $ids = [];
        foreach ($comments as $item) {
            array_push($ids, $item->id);
            $item->delete();
        }

        $this->post(route('comments.force-delete'),['ids' => $ids])
            ->assertOk();
        foreach ($ids as $id) {
            $this->assertDatabaseMissing('comments', [
                'id' => $id
            ]);
        }
    }

    public function testCanRestoreAComment()
    {
        $course = factory(Course::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $comments = factory(Comment::class,10)->create([
            'text' => 'text',
            'commentable_id' => $course->id,
            'commentable_type' => 'App\models\Course',
            'user_id' => $user->id,
            'parent' => 0
        ]);
        $ids = [];
        foreach ($comments as $item) {
            array_push($ids, $item->id);
            $item->delete();
        }

        $this->post(route('comment.restore'), ['ids' => $ids])
            ->assertOk();
        foreach ($ids as $id) {
            $this->assertDatabaseHas('comments', [
                'id' => $id
            ]);
        }
    }


}
