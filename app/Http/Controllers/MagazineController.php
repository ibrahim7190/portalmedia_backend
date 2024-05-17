<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Magazine;
use  App\Http\Resources\MagazineResource;


class MagazineController extends Controller
{
    public function index()
    {
        $magazines = Magazine::all();
        $message = [
            "Message" => "Get All Data Done",
            "Data" =>  MagazineResource::collection($magazines??[]),
            "status" => 200,
        ];
        return response($message, 200);
    }

    public function store(Request $request)
    {
        $size = 1024 * 20;

        $request->validate([
            'title' => 'required|min:2|max:35|string',
            'description' => 'required|min:5|max:200|string',
            "file_demo" => "required|mimes:png,jpg,jpeg,pdf,mp4|max:$size",
            'auther' => "required|min:2|max:50|string"
        ]);

        //upload file name , location
        if ($request->hasFile('file_demo')) {
            $magazine_Data = $request->file('file_demo');
            $magazine_name = time() . $magazine_Data->getClientOriginalName();
            $location = public_path('./magazine/');
            $magazine_Data->move($location, $magazine_name);
        }



        $magazine = Magazine::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_demo' => $magazine_name,
            'auther'=>$request->auther,

        ]);

        $message = [
            "Message" => " Data Created Successfuly",
            "Data" =>  MagazineResource::make($magazine),
            "status" => 201,
        ];
        return response($message, 201);
    }

    public function update(Request  $request, $id)
    {

        $size = 1024 * 20;

        $magazine = Magazine::find($id);
        //upload file name , location
        $magazine_Data = $request->file('file_demo');
        if ($magazine_Data != null) {
            $request->validate([
                'title' => 'required|min:2|max:35|string',
                'description' => 'required|min:5|max:200|string',
                "file_demo" => "required|mimes:pdf,jpg,png,jpeg|max:$size",
                'auther' => "required|min:2|max:50|string"
            ]);
            $magazine_name = time() . $magazine_Data->getClientOriginalName();
            $location = public_path('./magazine/');
            $magazine_Data->move($location, $magazine_name);
            $path = public_path() . "/magazine/" . $magazine->file;
            unlink($path);
        } else {
            $magazine_name = $magazine->file_demo;
        }

        $magazine->update([
            'title' => $request->title,
            'description' => $request->description,
            'file_demo' => $magazine_name,
            'auther' => $request->auther
        ]);

        $message = [
            "Message" => " Data Updated Successfuly",
            "Data" => MagazineResource::make($magazine),
            "status" => 201,
        ];
        return response($message, 201);
    }

    public function delete($id)
    {
        $magazine = Magazine::destroy($id);
        $message = [
            "Message" => "Data Deleted Successful",
            "Data"=> $magazine,
            "status" => 200
        ];
        // $path = public_path() . "/magazine/" . $magazine->file;
        // unlink($path);
        return response($message, 200);
    }
}
