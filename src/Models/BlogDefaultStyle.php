<?php


namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogDefaultStyle extends Model
{

    use SoftDeletes;

    protected $table = "tik_cms_blog_default_style";

    protected $fillable = [
        'featured_image_height',
        'featured_image_width',
        'blog_title_font_family',
        'blog_title_font_size',
        'blog_title_font_weight',
        'blog_title_color',
        'blog_content_font_family',
        'blog_content_font_size',
        'blog_content_font_weight'
    ];

}
