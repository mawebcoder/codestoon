<?php

namespace App\Http\Controllers\api\v1\admin\comment;

use App\Http\Controllers\Controller;
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
        //TODO GET ALL COMMENTS OF THE SYSTEM IN PAGINATION MODE
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //TODO VALIDATION OF THE STORE COMMENT
    public function store(Request $request)
    {
        $type = $request->type;

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    //TODO VALIDATION OF THE UPDATE COMMENT IN THE SYSTEM
    public function update(Request $request, Comment $comment)
    {
        //TODO  UPDATE COMMENT IN THE SYSTEM
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //TODO SOFT DELETE OF THE COMMENT IN THE SYSTEM
    }

    //TODO VALIDATION OF  DELETE MULTIPLE COMMENT
    public function deleteMultiple()
    {
        //TODO DELETE MULTIPLE COMMENT
    }

    //TODO VALIDATION OF THE FORCE DELETE OF THE COMMENT IN THE SYSTEM
    public function forceDelete()
    {
        //TODO FORCE DELETE COMMENT
    }

    //TODO VALIDATION OF THE RESTORE COMMENTS
    public function restoreComments()
    {
        //TODO RESTORE COMMENTS IN THE SYSTEM
    }

    public function getTrashedComments()
    {
        //TODO GET TRASHED COMMENTS
    }
}
