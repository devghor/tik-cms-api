<?php


namespace Tikweb\TikCmsApi\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\BlogPostComments;


class BlogPostCommentsController extends Controller
{
    public function create(Request $request) {

        $comment = BlogPostComments::create([
            'user_id' => Auth::user()->id,
            'blog_id' => request()->get('blog_id'),
            'comment' => $request->data['comment']
        ]);
        if($comment) {
            return response()->json(['message' => "Comment added."]);
        }
        else {
            return response()->json(['message' => "Something wrong."]);
        }
    }

    public function getAllCommentsForSpecificBlog(Request $request) {

//        $comments = BlogPostComments::select('comment')
//            ->where('blog_id', request()->get('blog_id'))
//            ->get();

        $comments = DB::table('tik_cms_blog_post_comments')
            ->where('tik_cms_blog_post_comments.blog_id', request()->get('blog_id'))
            ->join('users', "users.id", "=", "tik_cms_blog_post_comments.user_id")
//            ->join('tik_cms_blog_comment_replies', "tik_cms_blog_comment_replies.comment_id", "=", "tik_cms_blog_post_comments.id")
            ->select('users.id', 'users.name as user_name',
            'tik_cms_blog_post_comments.id as comment_id', 'tik_cms_blog_post_comments.comment as comment', 'tik_cms_blog_post_comments.created_at as comment_time')
            ->get();

        if($comments) {
            foreach ($comments as $comment) {
                $replies = DB::table('tik_cms_blog_comment_replies')
                    ->where("tik_cms_blog_comment_replies.comment_id", $comment->comment_id)
                    ->join('users', "users.id", "=", "tik_cms_blog_comment_replies.user_id")
                    ->select('users.id', 'users.name as user_name',
                    'tik_cms_blog_comment_replies.id as reply_id', 'tik_cms_blog_comment_replies.reply_message as reply_message')
                    ->get();
                $comment->replies = $replies;
            }
        }

        return response()->json(['data' => $comments]);
    }

}
