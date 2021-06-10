<?php


namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPostReactions extends Model
{

    use SoftDeletes;
    protected $table = "tik_cms_blog_post_reactions";
    protected $fillable = [
        'user_id',
        'blog_id',
        'reaction_type'
    ];
}
