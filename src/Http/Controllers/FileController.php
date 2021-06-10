<?php


namespace Tikweb\TikCmsApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function uploadStoreImageDatabase(Request $request) {

        if($request->hasfile('file'))
        {
            $imageName = time().'.'.$request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('images'), $imageName);
            $hostname = env("APP_URL");
            $url = $hostname.'/'.'vvveb-api/laravel/public/images'.'/'.$imageName;

            $image = [
                'type'  => 'image',
                'name'  => $imageName,
                'src'   => $url,
                'height'=> 350,
                'width' => 250,
            ];

            return response()->json(['data' => $image]);
        }

        return response()->json(['data' => "Something wrong"]);

    }

}
