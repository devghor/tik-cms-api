<?php

namespace Tikweb\TikCmsApi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebPage extends Model
{
    protected $table = "tik_cms_web_pages";

    use SoftDeletes;

    protected $fillable = [
        'name',
        'html',
        'published_html',
        'status',
        'language',
        'has_changes',
        'page_group',
        'url'
    ];
}
