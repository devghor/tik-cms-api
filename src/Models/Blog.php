<?php

namespace Tikweb\TikCmsApi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{


    use SoftDeletes;

    protected $table = "tik_cms_blogs";

    protected $fillable = [
        'title',
        'short_description',
        'featured_image',
        'content',
        'status',
        'author',
        'language',
        'category',
        'type',
        'tags',
        'url'
    ];
}
