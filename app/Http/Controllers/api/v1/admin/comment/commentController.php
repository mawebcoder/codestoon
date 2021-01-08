<?php

namespace App\Http\Controllers\api\v1\admin\Comment;

use App\Http\Controllers\Controller;
use App\models\Comment;
use Illuminate\Http\Request;

class commentController extends Controller
{
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //TODO STORE COMMENT IN THE SYSTEM
    }

    /**
     * Display the specified resource.
     *
     * @param \App\models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
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
