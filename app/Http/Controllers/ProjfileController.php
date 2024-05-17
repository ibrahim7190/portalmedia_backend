<?php

namespace App\Http\Controllers;

use App\Models\Projfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjfileController extends Controller
{
    public function index()
    {
        $projfiles = Projfile::all();
        return response()->json($projfiles);
    }

    public function show($id)
    {
        $projfile = Projfile::find($id);
        if (!$projfile) {
            return response()->json(['error' => 'Projfile not found'], 404);
        }
        return response()->json($projfile);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'projfile_path' => 'required|string',
        ]);

        $projfile = Projfile::create($request->all());
        return response()->json($projfile, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'projfile_path' => 'required|string',
        ]);

        $projfile = Projfile::find($id);
        if (!$projfile) {
            return response()->json(['error' => 'Projfile not found'], 404);
        }

        $projfile->update($request->all());
        return response()->json($projfile);
    }

    public function destroy($id)
    {
        $projfile = Projfile::find($id);
        if (!$projfile) {
            return response()->json(['error' => 'Projfile not found'], 404);
        }

        $projfile->delete();
        return response()->json(null, 204);
    }
    public function getProjfilesByProjectId($projectId)
    {
        $projfiles = Projfile::where('project_id', $projectId)->get();
        return response()->json($projfiles);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'projfile' => 'required', // Adjust max file size as needed
        ]);

        // Generate a unique filename for the projfile
        $filename = Str::random(20) . '.' . $request->file('projfile')->getClientOriginalExtension();

        // Store the uploaded file in the public disk

        $filePath = $request->file('projfile')->storeAs('projfiles', $filename, 'public');

        // Create the projfile record in the database
        $projfile = Projfile::create([
            'project_id' => $request->input('project_id'),
            'projfile_path' => $filePath,
        ]);

        // Append full URL to the projfile object
        $projfile->full_path = asset('storage/' . $filePath);

        return response()->json($projfile, 201);
    }

    public function getUploadedProjfilesByProjectID1($projectId)
    {
        $projfiles = Projfile::where('project_id', $projectId)->get();
        // Append full path to each projfile object
        $projfiles->each(function ($projfile) {
            $projfile->full_path = Storage::disk('projfiles')->path($projfile->projfile_path);
        });

        return response()->json($projfiles);
    }
    public function getUploadedProjfilesByProjectID($projectId)
    {
        $projfiles = Projfile::where('project_id', $projectId)->get();

        // Append full URL to each projfile object
        $projfiles->each(function ($projfile) {
            $projfile->full_path = asset('storage/' . $projfile->projfile_path);
        });

        return response()->json($projfiles);
    }

}
