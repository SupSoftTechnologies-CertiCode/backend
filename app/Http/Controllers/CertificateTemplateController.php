<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\File;


class CertificateTemplateController extends Controller
{



    public function index()
    {
        try {
            $templates = CertificateTemplate::all();
            return response()->json($templates);
        } catch (\Exception $e) {
            \Log::error('Error fetching certificate templates: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch templates'], 500);
        }
    }

    public function show($id)
    {
        $template = CertificateTemplate::findOrFail($id);
        return response()->json($template);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pdf_file' => 'required|mimes:pdf|max:10240',
        ]);

        $filePath = null;

        if ($request->hasFile('pdf_file')) {
            $filePath = $request->file('pdf_file')->store('certificates', 'public');
        }
        // $file = $request->file('pdf_file');
        // $filePath = $request->file('image')->store('images', 'public');
        // $filename = time() . '_' . $file->getClientOriginalName();
        // $file->move(public_path('certificates'), $filename);

        $template = CertificateTemplate::create([
            'name' => $request->name,
            'pdf_filename' => $filePath,
        ]);

        return response()->json(['message' => 'Template uploaded successfully', 'template' => $template]);
    }

    public function update(Request $request, $id)
    {
        $template = CertificateTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('pdf_file')) {
            if ($template->pdf_filename) {
                $oldPath = storage_path('app/public/' . $template->pdf_filename);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $filePath = $request->file('pdf_file')->store('certificates', 'public');
            $template->pdf_filename = $filePath;
        }

        $template->name = $request->name;
        $template->save();

        return response()->json(['message' => 'Template updated successfully', 'template' => $template]);
    }

    public function destroy($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        if ($template->pdf_filename) {
            $filePath = storage_path('app/public/' . $template->pdf_filename);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $template->delete();

        return response()->json(['message' => 'Template deleted successfully']);
    }

    public function viewTemplate($filename)
    {
        $path = storage_path('app/public/certificates/' . $filename);

        if (!File::exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $frontendUrl = 'http://localhost:5173';

        $fileContent = file_get_contents($path);
        $fileSize = filesize($path);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
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



    public function saveJsonLayout(Request $request, $id)
    {
        $request->validate([
            'json_layout' => 'required|json',
        ]);

        $template = CertificateTemplate::findOrFail($id);
        $template->json_layout = $request->json_layout;
        $template->save();

        return response()->json(['message' => 'JSON layout saved successfully']);
    }

    public function proxyImage(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');

        try {
            $imageContent = file_get_contents($url);

            if ($imageContent === false) {
                return response()->json(['error' => 'Failed to fetch image'], 404);
            }

            $contentType = 'image/jpeg';

            $headers = get_headers($url, 1);
            if (isset($headers['Content-Type'])) {
                $contentType = is_array($headers['Content-Type'])
                    ? $headers['Content-Type'][0]
                    : $headers['Content-Type'];
            }

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');

            return response($imageContent, 200, [
                'Content-Type' => $contentType,
                'Access-Control-Allow-Origin' => $frontendUrl,
                'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to proxy image: ' . $e->getMessage()], 500);
        }
    }


}
