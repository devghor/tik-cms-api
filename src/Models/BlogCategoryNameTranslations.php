<?php


namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategoryNameTranslations extends Model
{
    use SoftDeletes;

    protected $table = "tik_cms_blog_category_name_translations";

    protected $fillable = [
        'category_id',
        'language_id',
        'translated_name',
        'parent_change_tracker'
    ];
}
