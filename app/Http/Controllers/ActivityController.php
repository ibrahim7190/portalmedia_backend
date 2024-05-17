<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Activity;
use  App\Http\Resources\ActivityResource;


class ActivityController extends Controller
{
    public function index()
    {
        $activity = Activity::all();
        $message = [
            "Message" => "Get All Data Done",
            "Data" =>ActivityResource::collection($activity??[]),
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
            "file_demo" => "required|mimes:png,jpg,pdf|max:$size"
        ]);

        //upload file name , location
        if ($request->hasFile('file_demo')) {
            $activity_Data = $request->file('file_demo');
            $activity_name = time() . $activity_Data->getClientOriginalName();
            $location = public_path('./activity/');
            $activity_Data->move($location, $activity_name);
        }


        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_demo' => $activity_name,

        ]);

        $message = [
            "Message" => " Data Created Successfuly",
            "Data" => ActivityResource::make($activity),
            "status" => 201,
        ];
        return response($message, 201);
    }

    public function update(Request  $request, $id)
    {

        $size = 1024 * 20;

        $activity = Activity::find($id);
        //upload file name , location
        $activity_Data = $request->file('file_demo');
        if ($activity_Data != null) {
            $request->validate([
                'title' => 'required|min:2|max:35|string',
                'description' => 'required|min:5|max:200|string',
                "file_demo" => "required|mimes:png,jpg,pdf|max:$size"
            ]);
            $activity_name = time() . $activity_Data->getClientOriginalName();
            $location = public_path('./activity/');
            $activity_Data->move($location, $activity_name);
            $path = public_path() . "/activity/" . $activity->file;
            unlink($path);
        } else {
            $activity_name = $activity->file_demo;
        }

        $activity->update([
            'title' => $request->title,
            'description' => $request->description,
            'file_demo' => $activity_name,
        ]);

        $message = [
            "Message" => " Data Updated Successfuly",
            "Data" => $activity,
            "status" => 201,
        ];
        return response($message, 201);
    }

    public function delete($id)
    {
        $activity = Activity::destroy($id);
        $message = [
            "Message" => "Data Deleted Successful",
            "Data"=> $activity,
            "status" => 200
        ];
       //  $path = public_path() . "/activity/" . $activity->file;
        // unlink($path);
        return response($message, 200);
    }
}
