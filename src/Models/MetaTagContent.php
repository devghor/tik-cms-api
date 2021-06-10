<?php

namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetaTagContent extends Model
{
    use SoftDeletes;

    protected $table = "tik_cms_meta_tag_contents";

    protected $fillable = [
        'item_id',
        'item_type',
        'title',
        'keywords',
        'description',
    ];
}
