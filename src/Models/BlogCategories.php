<?php


namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategories extends Model
{
    use SoftDeletes;

    protected $table = "tik_cms_blog_categories";

    protected $fillable = [
        'category_name',
    ];

}
