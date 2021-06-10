<?php


namespace Tikweb\TikCmsApi\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubPage extends Model
{
    protected $table = "tik_cms_sub_pages";

    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'html',
        'published_html',
        'status',
        'type',
        'url'
    ];
}
