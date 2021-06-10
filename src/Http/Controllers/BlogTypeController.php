<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\BlogType;

class BlogTypeController extends Controller
{
    public function create(Request $request) {
        $blog_type = BlogType::create([
            'type_name' => $request->data['type_name'],
        ]);

        if($blog_type) {
            return response()->json(['data' => "New type added."]);
        }
        else {
            return response()->json(['data' => "Type con not be added."]);
        }
    }

    public function showAllBlogTypes() {
        $blog_types = BlogType::select('id', 'type_name')->get();
        if($blog_types) {
            return response()->json(['data' => $blog_types]);
        }
        else {
            return response()->json(['data' => "Something wrong."]);
        }
    }
}
