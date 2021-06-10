<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\BlogCommentReplies;


class BlogCommentRepliesController extends Controller
{
    public function create(Request $request) {

        $reply = BlogCommentReplies::create([
            'user_id' => Auth::user()->id,
            'comment_id' => $request->data['comment_id'],
            'reply_message' => $request->data['reply_message']
        ]);
        if($reply) {
            return response()->json(['message' => "Reply added."]);
        }
        else {
            return response()->json(['message' => "Something wrong."]);
        }
    }

}
