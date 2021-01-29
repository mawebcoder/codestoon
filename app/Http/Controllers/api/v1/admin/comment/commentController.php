<?php

namespace App\Http\Controllers\api\v1\admin\comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\comment\DeleteCommentValidation;
use App\Http\Requests\comment\StoreCommentValidation;
use App\models\Comment;
use Illuminate\Http\Request;

class commentController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $empty_failed_message = ['message' => 'failed', 'data' => null];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $comments=Comment::withTrashed()->paginate(30);
       return  $comments->isNotEmpty()?
           response(['message'=>'success','data'=>$comments]):
           response($this->empty_success_message,204);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreCommentValidation $request)
    {
        $type = request()->type;

        $types = [
            'video' => 'App\models\Video',
            'course' => 'App\models\Course',
            'article' => 'App\models\Article'
        ];

        $result = Comment::query()->create([
            'user_id' => auth()->user()->id,
            'text' => request()->text,
            'commentable_id' => request()->commentable_id,
            'commentable_type' => $types[$type],
            'parent' => request()->parent ?? 0,
        ]);

        return $result ?
            response($this->empty_success_message,201) :
            response($this->empty_failed_message, 500);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        return  response(['message'=>'success','data'=>$comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Comment $comment
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, Comment $comment)
    {
        $comment->update([
            'active'=>request()->active ?1:0
        ]);
        return  response($this->empty_success_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
       $comment->query()->delete();
       return  response($this->empty_success_message);
    }


    public function deleteMulti(DeleteCommentValidation $deleteCommentValidation)
    {
        Comment::query()->whereIn('id',request()->ids)->delete();
        return response($this->empty_success_message);
    }


    public function forceDelete(DeleteCommentValidation $deleteCommentValidation)
    {
        Comment::onlyTrashed()->whereIn('id',request()->ids)
            ->forceDelete();
        return response($this->empty_success_message);
    }


    public function restore(DeleteCommentValidation $deleteCommentValidation)
    {
        Comment::onlyTrashed()->whereIn('id',request()->ids)
            ->restore();
        return response($this->empty_success_message);
    }

    public function getTrashed()
    {
        $trashed_comments=Comment::onlyTrashed()->paginate(30);

        return $trashed_comments->isNotEmpty() ?
            response(['message'=>'success',$trashed_comments]):
            response($this->empty_success_message,204);
    }
}
