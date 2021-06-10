<?php

namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Tikweb\TikCmsApi\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function create(Request $request) {
        $language = Language::create([
            'language'  => $request->data['language'],
            'short_code' => $request->data['short_code'],
            'flag_src' => $request->data['flag_src'],
        ]);

        if($language) {
            return response()->json(['message'=>''.$request->data['language'].' Language added.']);
        }
        else {
            return response()->json(['message'=>'sorry, something went wrong']);
        }
    }

    public function showAll()
    {
        $languages = Language::select('id','language', 'short_code', 'flag_src')->get();
        if($languages){
            return response()->json(['languages' => $languages]);
        }
        else {
            return response()->json(['languages' => "no languages found"]);
        }

    }
}
