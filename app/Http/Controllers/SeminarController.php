<?php

namespace App\Http\Controllers;

use App\Models\ArchiveSeminar;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_of_seminars = Seminar::orderBy('created_at', 'desc')->get();

        return response()->json($list_of_seminars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        $validated = $request->validate([
            'name_of_seminar' => 'required|string|max:255',
            'topics' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'speaker_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'speaker_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'seminar_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'about_the_speaker' => 'required|string',
            'certificate_template_id' => 'required|integer|exists:certificate_templates,id',
            'price' => 'required|integer',
        ]);

        if ($validated) {
            if ($request->hasFile('speaker_image')) {
                $validated['speaker_image'] = $request->file('speaker_image')->store('images/speaker_images', 'public');
            }

            if ($request->hasFile('seminar_image')) {
                $validated['seminar_image'] = $request->file('seminar_image')->store('images/seminar_images', 'public');
            }
        }


        // if ($request->hasFile('certificate_template')) {
        //     $validated['certificate_template'] = $request->file('certificate_template')->store('files/certificate_templates', 'public');
        // }

        $createdSeminar = Seminar::create($validated);

        return response()->json([
            'message' => 'Seminar created successfully!',
            'seminar' => $createdSeminar
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seminar $seminar)
    {
        return response()->json([
            'message' => 'Selected Seminar',
            'seminar' => $seminar
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seminar $seminar)
    {
        Log::info($request->all());
        $validated = $request->validate([
            'name_of_seminar' => 'required|string|max:255',
            'topics' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'speaker_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'speaker_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'seminar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'about_the_speaker' => 'required|string',
            'certificate_template_id' => 'required|integer|exists:certificate_templates,id',
            'price' => 'required|integer',
        ]);

        if ($request->hasFile('speaker_image')) {
            if ($seminar->speaker_image) {
                $oldPath = 'images/speaker_images/' . basename($seminar->speaker_image);
                $archivePath = 'images/archived_images/' . basename($seminar->speaker_image);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $archivePath);
                }
            }
            $validated['speaker_image'] = $request->file('speaker_image')->store('images/speaker_images', 'public');
        } else {
            unset($validated['speaker_image']);
        }

        if ($request->hasFile('seminar_image')) {
            if ($seminar->speaker_image) {
                $oldPath = 'images/seminar_images/' . basename($seminar->speaker_image);
                $archivePath = 'images/archived_images/' . basename($seminar->speaker_image);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $archivePath);
                }
            }
            $validated['seminar_image'] = $request->file('seminar_image')->store('images/seminar_images', 'public');
        } else {
            unset($validated['seminar_image']);
        }

        // if ($request->hasFile('certificate_template')) {
        //     if ($seminar->certificate_template) {
        //         $oldPath = 'files/certificate_templates/' . basename($seminar->certificate_template);
        //         $archivePath = 'files/archived_files/' . basename($seminar->certificate_template);

        //         if (Storage::disk('public')->exists($oldPath)) {
        //             Storage::disk('public')->move($oldPath, $archivePath);
        //         }
        //     }
        //     $validated['certificate_template'] = $request->file('certificate_template')->store('files/certificate_templates', 'public');
        // } else {
        //     unset($validated['certificate_template']);
        // }

        $seminar->update($validated);

        return response()->json([
            'message' => 'Seminar updated successfully.',
            'seminar' => $seminar
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seminar $seminar)
    {
        $seminar->delete();

        return response()->json([
            'message' => 'Seminar Deleted.',
            'seminar' => $seminar
        ], 200);
    }

    public function viewImage($path)
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!File::exists($fullPath)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $frontendUrl = 'http://localhost:5173';

        $fileContent = file_get_contents($fullPath);
        $fileSize = filesize($fullPath);
        $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Access-Control-Allow-Origin' => $frontendUrl,
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept',
            'Access-Control-Allow-Credentials' => 'true',
        ];

        return response()->stream(
            function() use ($fileContent) {
                echo $fileContent;
            },
            200,
            $headers
        );
    }
}
