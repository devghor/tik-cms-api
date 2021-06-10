<?php


namespace Tikweb\TikCmsApi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCommentReplies extends Model
{
    use SoftDeletes;
    protected $table = "tik_cms_blog_comment_replies";
    protected $fillable = [
        'user_id',
        'comment_id',
        'reply_message'
    ];

}
