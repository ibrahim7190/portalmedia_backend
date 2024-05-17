<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScreenshotController extends Controller
{
    public function index()
    {
        $screenshots = Screenshot::all();
        return response()->json($screenshots);
    }

    public function show($id)
    {
        $screenshot = Screenshot::find($id);
        if (!$screenshot) {
            return response()->json(['error' => 'Screenshot not found'], 404);
        }
        return response()->json($screenshot);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'screenshot_path' => 'required|string',
        ]);

        $screenshot = Screenshot::create($request->all());
        return response()->json($screenshot, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'screenshot_path' => 'required|string',
        ]);

        $screenshot = Screenshot::find($id);
        if (!$screenshot) {
            return response()->json(['error' => 'Screenshot not found'], 404);
        }

        $screenshot->update($request->all());
        return response()->json($screenshot);
    }

    public function destroy($id)
    {
        $screenshot = Screenshot::find($id);
        if (!$screenshot) {
            return response()->json(['error' => 'Screenshot not found'], 404);
        }

        $screenshot->delete();
        return response()->json(null, 204);
    }
    public function getScreenshotsByProjectId($projectId)
    {
        $screenshots = Screenshot::where('project_id', $projectId)->get();
        return response()->json($screenshots);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
        ]);

        // Generate a unique filename for the screenshot
        $filename = Str::random(20) . '.' . $request->file('screenshot')->getClientOriginalExtension();

        // Store the uploaded file in the public disk

        $filePath = $request->file('screenshot')->storeAs('screenshots', $filename, 'public');

        // Create the screenshot record in the database
        $screenshot = Screenshot::create([
            'project_id' => $request->input('project_id'),
            'screenshot_path' => $filePath,
        ]);

        // Append full URL to the screenshot object
        $screenshot->full_path = asset('storage/' . $filePath);

        return response()->json($screenshot, 201);
    }

    public function getUploadedScreenShotsByProjectID1($projectId)
    {
        $screenshots = Screenshot::where('project_id', $projectId)->get();
        // Append full path to each screenshot object
        $screenshots->each(function ($screenshot) {
            $screenshot->full_path = Storage::disk('screenshots')->path($screenshot->screenshot_path);
        });

        return response()->json($screenshots);
    }
    public function getUploadedScreenShotsByProjectID($projectId)
    {
        $screenshots = Screenshot::where('project_id', $projectId)->get();

        // Append full URL to each screenshot object
        $screenshots->each(function ($screenshot) {
            $screenshot->full_path = asset('storage/' . $screenshot->screenshot_path);
        });

        return response()->json($screenshots);
    }

}
