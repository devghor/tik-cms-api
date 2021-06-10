<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\BlogDefaultStyle;

class BlogDefaultStyleController extends Controller
{
    public function create(Request $request) {

        $style = BlogDefaultStyle::create([
            'featured_image_height'     => "590px",
            'featured_image_width'      => "1200px",
            'blog_title_font_family'    => $request->data['blog_title_font_family'],
            'blog_title_font_size'      => $request->data['blog_title_font_size'],
            'blog_title_font_weight'    => $request->data['blog_title_font_weight'],
            'blog_title_color'          => $request->data['blog_title_color'],
            'blog_content_font_family'  => $request->data['blog_content_font_family'],
            'blog_content_font_size'    => $request->data['blog_content_font_size'],
            'blog_content_font_weight'  => $request->data['blog_content_font_weight'],
        ]);
        if($style) {
            return response()->json(['data' => "Style added."]);
        }
        else {
            return response()->json(['data' => "Something wrong. Category can not be added."]);
        }
    }

    public function updateStyle(Request $request) {

        $has_style = BlogDefaultStyle::all();

        if(!$has_style->isEmpty()) {
            $style = BlogDefaultStyle::first()
                ->update([
                    'featured_image_height'     => "590px",
                    'featured_image_width'      => "1200px",
                    'blog_title_font_family'    => $request->data['blog_title_font_family'],
                    'blog_title_font_size'      => $request->data['blog_title_font_size'],
                    'blog_title_font_weight'    => $request->data['blog_title_font_weight'],
                    'blog_title_color'          => $request->data['blog_title_color'],
                    'blog_content_font_family'  => $request->data['blog_content_font_family'],
                    'blog_content_font_size'    => $request->data['blog_content_font_size'],
                    'blog_content_font_weight'  => $request->data['blog_content_font_weight'],
                ]);
            if($style) {
                return response()->json(['data' => "Style updated."]);
            }
            else {
                return response()->json(['data' => "Something wrong. Category can not be added."]);
            }
        }
        else {
            $style = BlogDefaultStyle::create([
                'featured_image_height'     => "590px",
                'featured_image_width'      => "1200px",
                'blog_title_font_family'    => $request->data['blog_title_font_family'],
                'blog_title_font_size'      => $request->data['blog_title_font_size'],
                'blog_title_font_weight'    => $request->data['blog_title_font_weight'],
                'blog_title_color'          => $request->data['blog_title_color'],
                'blog_content_font_family'  => $request->data['blog_content_font_family'],
                'blog_content_font_size'    => $request->data['blog_content_font_size'],
                'blog_content_font_weight'  => $request->data['blog_content_font_weight'],
            ]);
            if($style) {
                return response()->json(['data' => "Style added."]);
            }
            else {
                return response()->json(['data' => "Something wrong. Category can not be added."]);
            }
        }

    }

    public function  showDefaultStyle() {
        $style = BlogDefaultStyle::first();
        if($style) {
            return response()->json(['data' => $style]);
        }
        else {
            return response()->json(['message' => "Something wrong."]);
        }
    }
}
