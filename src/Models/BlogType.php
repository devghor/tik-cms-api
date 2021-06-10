<?php


namespace Tikweb\TikCmsApi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogType extends Model
{
    use SoftDeletes;

    protected $table = "tik_cms_blog_types";

    protected $fillable = [
        'type_name',
    ];

}
