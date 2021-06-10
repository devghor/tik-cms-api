<?php


namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageGroup extends Model
{
    use SoftDeletes;

    protected $table = "tik_cms_page_groups";

    protected $fillable = [
        'group_name',
    ];

}
