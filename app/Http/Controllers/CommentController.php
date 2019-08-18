<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentController extends Controller
{
    //
    protected function create()
    {   
        $data['comment_body'] = request('comment_body');
        $data['article_slug'] = request()->slug;
        $data['user_uuid'] = request()->user->uuid;
        $comment = Comment::createComment($data);
        if ($comment['errors']) {
            return response()->json([
                'errors' => $comment['errors'],
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $comment,
            'success' => true
        ], \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    protected function update()
    {
        $updateComment = Comment::updateComment(request()->all(), request()->comment);
        if ($updateComment['errors']) {
            return response()->json([
                'errors' => $updateComment['errors'],
                'success' => false
            ], \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'success' => $updateComment,
            'message' => 'comment updated successfully'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }

    protected function destroy()
    {
        $deleteComment = Comment::deleteComment(request()->comment);

        if ($deleteComment) {
            return response()->json([
                'success' => $deleteComment,
                'message' => 'comment deleted successfully'
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        }

        return response()->json([
            'success' => $deleteComment,
            'message' => 'comment wasnot deleted'
        ], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        
    }
}
