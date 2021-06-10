<?php


namespace Tikweb\TikCmsApi\Http\Controllers;


use Tikweb\TikCmsApi\Models\FacebookTagContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacebookTagContentController extends Controller
{
    public function showAll()
    {
        $meta_tag_contents = FacebookTagContent::all();
        if($meta_tag_contents){
            return response()->json(['meta_tag_contents' => $meta_tag_contents]);
        }
        return response()->json(['meta_tag_contents' => "meta tag contents not found"]);
    }

    public function showItemFacebookTag()
    {
        $meta_tag_content = FacebookTagContent::where('item_id', request()->get('item_id'))
            ->where('item_type',request()->get('item_type'))
            ->get();

        if($meta_tag_content){
            return response()->json(['meta_tag_contents' => $meta_tag_content]);
        }
        return response()->json(['meta_tag_content' => "meta tag contents not found"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateFacebookTagContent(Request $request)
    {
        $meta_tag = FacebookTagContent::where('id', request()->get('item_id'))
            ->update([
                'title'       => $request->data['title'],
                'keywords'    => $request->data['keywords'],
                'description' => $request->data['description'],
                'img_src'     => $request->data['img_src']
            ]);
        if($meta_tag) {
            return response()->json(['success'=>'meta tag content updated']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }
}
