<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tikweb\TikCmsApi\Models\PageGroup;
use Tikweb\TikCmsApi\Models\WebPage;

class PageGroupController extends Controller
{

    public function create(Request $request) {

        if($request->data['group_name']) {
            $group = PageGroup::create([
                'group_name' => $request->data['group_name'],
            ]);
            if($group) {
                return response()->json(['data' => "Group added."]);
            }
            else {
                return response()->json(['data' => "Something wrong. Group can not be added."]);
            }
        }
        else {
            return response()->json(['data' => "Please pass a string"]);
        }
    }

    public function showAllPageGroups() {
        $groups = PageGroup::select('id', 'group_name')->get();
        if($groups) {
            return response()->json(['data' => $groups]);
        }
        else {
            return response()->json(['message' => "Something wrong."]);
        }
    }

    public function updateGroupName(Request $request) {
        $group = PageGroup::where('id', request()->get('group_id'))
            ->update([
                'group_name' => $request->data['new_name'],
            ]);
        if($group) {
            return response()->json(['data' => "Name updated."]);
        }
        else {
            return response()->json(['data' => "Something wrong."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $group = PageGroup::where('id', request()->get('item_id'))
            ->first();

        if($group) {
            $assigned_pages = WebPage::where('page_group', $group['id'])
                ->get();

            if(!$assigned_pages->isEmpty()) {
                return response()->json(['data' => "Some pages are already assigned in these group.\nPlease delete those pages."]);
            }
            else {
                $group->delete();
                return response()->json(['data' => "Group deleted successfully!"]);
            }
        }
        else {
            return response()->json(['data' => "something wrong!"]);
        }
    }

    public function showAllTrashGroups()
    {
        $groups = PageGroup::select('id', 'group_name')
            ->onlyTrashed()
            ->get();
        if($groups){
            return response()->json(['data' => $groups]);
        }
        else {
            return response()->json(['data' => "No group is in trash."]);
        }
    }

    public function restore()
    {
        $group = PageGroup::onlyTrashed()->find(request()->get('group_id'));
        if($group){
            $group->restore();
            return response()->json(['data' => "Group restored."]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

    public function permanentDelete()
    {
        $group = PageGroup::onlyTrashed()->find(request()->get('item_id'));
        if($group){
            $group->forceDelete();
            return response()->json(['data' => "Group deleted successfully!"]);
        }
        else {
            return response()->json(['data' => "Something wrong!"]);
        }
    }

}
