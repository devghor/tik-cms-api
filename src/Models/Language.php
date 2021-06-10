<?php

namespace Tikweb\TikCmsApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    protected $table="tik_cms_languages";

    use SoftDeletes;

    protected $fillable = [
        'language',
        'short_code',
        'flag_src'
    ];
}
