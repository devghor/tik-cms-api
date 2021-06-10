<?php

namespace Tikweb\TikCmsApi\Http\Controllers;
use Tikweb\TikCmsApi\Models\GoogleTagContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoogleTagContentController extends Controller
{
    public function showAll()
    {
        $meta_tag_contents = GoogleTagContent::all();
        if($meta_tag_contents){
            return response()->json(['meta_tag_contents' => $meta_tag_contents]);
        }
        else {
            return response()->json(['mata_tag_contents' => "meta tag contents not found"]);
        }

    }

    public function showItemGoogleTag()
    {
        $meta_tag_content = GoogleTagContent::where('item_id', request()->get('item_id'))
            ->where('item_type',request()->get('item_type'))
            ->get();

        if($meta_tag_content){
            return response()->json(['meta_tag_contents' => $meta_tag_content]);
        }
        else {
            return response()->json(['meta_tag_content' => "meta tag contents not found"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateGoogleTagContent(Request $request)
    {
        $meta_tag = GoogleTagContent::where('id', request()->get('item_id'))
            ->update([
                'title'       => $request->data['title'],
                'keywords'    => $request->data['keywords'],
                'description' => $request->data['description']
            ]);
        if($meta_tag) {
            return response()->json(['success'=>'meta tag content updated']);
        }
        else {
            return response()->json(['error'=>'something wrong!']);
        }
    }
}
