<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Project;
use  App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        $message = [
            "Message" => "Get All Data Done",
            "Data" =>ProjectResource::collection($projects??[]),
            "status" => 200,
        ];
        return response($message, 200);

        //return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_demo' => 'nullable|string',
            'status' => 'nullable|in:pending,approved',
            'coordinator_id' => 'required|exists:accounts,id',
            'StarRating' => 'nullable|integer|min:0|max:5',
            'department_id' => 'nullable|exists:departments,id',
            'Year' => 'nullable|integer',
        ]);

        $project = Project::create($validatedData);
        return response()->json($project, 201);
    }
    public function store2(Request $request)
    {
        $size = 1024 * 50;
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_demo' => "nullable|file|mimes:mp4|max:$size", // Adjust max file size as needed
            'status' => 'nullable|in:pending,approved',
            'coordinator_id' => 'required|exists:accounts,id',
            'StarRating' => 'nullable|integer|min:0|max:5',
            'department_id' => 'nullable|exists:departments,id',
            'Year' => 'nullable|integer',
        ]);


        // Check if a file is uploaded
        if ($request->hasFile('video_demo')) {
            // Store the file in the storage/app/public directory
            $path = $request->file('video_demo')->store('public/videos');

            // Update the 'video_demo' field in $validatedData with the file path
            $validatedData['video_demo'] = $path;
        }

        $s = Project::create($validatedData);
        $project = ProjectResource::make($s);
        return response()->json($project, 201);
    }
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_demo' => 'nullable|string',
            'file_proj' => 'nullable|mimes:pdf',
            'status' => 'nullable|in:pending,approved',
            'coordinator_id' => 'required|exists:accounts,id',
            'StarRating' => 'nullable|integer|min:0|max:5',
            'department_id' => 'nullable|exists:departments,id',
            'Year' => 'nullable|integer',
        ]);

        $project->update($validatedData);
        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json(null, 204);
    }


    public function getProjectsByDepartmentId($departmentId)
    {
        // Retrieve projects by department ID
        $projects = Project::where('department_id', $departmentId)->get();

        return response()->json($projects);
    }
    public function getProjectsByDepartmentIdWithVideo($departmentId)
{
    // Retrieve projects by department ID
    $projects = Project::where('department_id', $departmentId)->get();

    // Iterate through each project and append the full URL of the video demo
    foreach ($projects as $project) {
        if ($project->video_demo) {
            // Get the full path of the video demo
            $fullPath = Storage::url($project->video_demo);
            // Append the full URL to the project object
            $project->video_demo_full_path = url($fullPath);
        }
    }

    return response()->json($projects);
}
}
