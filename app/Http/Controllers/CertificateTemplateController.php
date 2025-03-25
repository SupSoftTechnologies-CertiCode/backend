<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\File;


class CertificateTemplateController extends Controller
{

    // 🔹 Archive a certificate template


    // 🔹 Get all certificate templates
    public function index()
    {
        return response()->json(CertificateTemplate::all());
    }

    // 🔹 Upload a new certificate template
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pdf_file' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = null;

        if ($request->hasFile('pdf_file')) {
            $filePath = $request->file('pdf_file')->store('certificates', 'public');
        }
        // $file = $request->file('pdf_file');
        // $filePath = $request->file('image')->store('images', 'public');
        // $filename = time() . '_' . $file->getClientOriginalName();
        // $file->move(public_path('certificates'), $filename);

        // Save to the database
        $template = CertificateTemplate::create([
            'name' => $request->name, // Store name
            'pdf_filename' => $filePath,
        ]);

        return response()->json(['message' => 'Template uploaded successfully', 'template' => $template]);
    }

    // 🔹 Update an existing template (Replace old PDF & Name)
    public function update(Request $request, $id)
    {
        $template = CertificateTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'pdf_file' => 'nullable|mimes:pdf|max:2048',
        ]);

        // If a new file is uploaded, delete old file & replace
        if ($request->hasFile('pdf_file')) {
            $oldFilePath = public_path('certificates/' . $template->pdf_filename);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }

            $file = $request->file('pdf_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('certificates'), $filename);
            $template->pdf_filename = $filename;
        }

        // Update name
        $template->name = $request->name;
        $template->save();

        return response()->json(['message' => 'Template updated successfully', 'template' => $template]);
    }

    // 🔹 Delete a certificate template
    public function destroy($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        // Delete file from storage
        $filePath = public_path('certificates/' . $template->pdf_filename);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete from database
        $template->delete();

        return response()->json(['message' => 'Template deleted successfully']);
    }
}
