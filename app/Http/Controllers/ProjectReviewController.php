<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProjectReview;
use Carbon\Carbon;

class ProjectReviewController extends Controller
{
    public function getReviewsByProjectId($projectId)
    {
        $reviews = ProjectReview::where('project_id', $projectId)->with('project:title')->get();
        return response()->json($reviews);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'account_id' => 'required|exists:accounts,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Set the review_date to the current datetime
        $validatedData['review_date'] = Carbon::now();

        $review = ProjectReview::create($validatedData);
        return response()->json($review, 201);
    }
}
