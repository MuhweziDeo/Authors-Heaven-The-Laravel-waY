<?php

namespace App\Models;

use App\Helpers\ErrorHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Comment extends Model
{
    //
    protected $fillable = ['article_slug', 'user_uuid', 'comment_body'];

    protected static $rules = [
        'comment_body' => ['string', 'required']
    ];


    public function user()
    {
       return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_slug', 'slug');
    }

    protected static function validator(Array $data)
    {

        return Validator::make($data, Comment::$rules);

    }


    protected static function checkIsOwner(Request $request, Comment $comment)
    {

        return $request->user->uuid === $comment->user_uuid;

    }

    protected static function findCommentById($id)
    {
        return Comment::find($id);
    }

    protected static function createComment(Array $data)
    {
        $validator = Comment::validator($data);

        if($validator->fails()){
            return [
                'errors' => ErrorHelper::formatErrors($validator)
            ];
        }


        return Comment::create([
            'comment_body' => $data['comment_body'],
            'article_slug' => $data['article_slug'],
            'user_uuid' => $data['user_uuid']
        ]);
    }

    protected static function updateComment(Array $data, Comment $comment)
    {
        if (count($data) === 0) {
            return [
                'errors' => 'Please provide atleast on value to update'
            ];
        }
        $validator = Comment::validator($data);

        if($validator->fails()){
            return [
                'errors' => ErrorHelper::formatErrors($validator)
            ];
        }

        return $comment->update($data);


    }

    protected static function deleteComment(Comment $comment)
    {
        return $comment->delete();
    }
}
