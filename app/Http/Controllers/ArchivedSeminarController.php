<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArchiveSeminar;
use App\Models\Seminar;
use App\Models\CertificateTemplate;
use App\Models\ArchivedCertificateTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ArchivedSeminarController extends Controller
{
    // Archive a certificate template
    public function cert_archive($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        // Move data to the archive table
        ArchivedCertificateTemplate::create([
            'id' => $template->id, // Preserve ID
            'name' => $template->name,
            'pdf_filename' => $template->pdf_filename,
        ]);
        

        // Delete from original table
        $template->delete();

        return response()->json(['message' => 'Template archived successfully']);
    }

    // Archive a seminar
    public function archive($id)
    {
        $seminar = Seminar::findOrFail($id);

        // Move data to the archive table
        ArchiveSeminar::create($seminar->toArray());

        // Delete from original table
        $seminar->delete();

        return response()->json(['message' => 'Seminar archived successfully']);
    }

    // Display archived seminars
    public function archive_display()
    {
        $archivedSeminars = ArchiveSeminar::all();
        return response()->json($archivedSeminars);
    }

    // Display archived certificate templates
    public function archived_certificates()
    {
        $archivedTemplates = ArchivedCertificateTemplate::all();
        return response()->json($archivedTemplates);
    }

    // Restore an archived seminar
    public function restore($id)
    {
        $archivedSeminar = ArchiveSeminar::findOrFail($id);

        // Move seminar back to the original table
        $seminar = Seminar::create($archivedSeminar->toArray());

        // Delete from archive
        $archivedSeminar->delete();

        return response()->json([
            'message' => 'Seminar restored successfully',
            'seminar' => $seminar
        ]);
    }

    public function restore_cert_template($id)
    {
        $archivedTemplate = ArchivedCertificateTemplate::findOrFail($id);

        // Move certificate back to the original table
        CertificateTemplate::create([
            'id' => $archivedTemplate->id, 
            'name' => $archivedTemplate->name,
            'pdf_filename' => $archivedTemplate->pdf_filename,
        ]);
        
        // Delete from archive
        $archivedTemplate->delete();

        return response()->json([
            'message' => 'Certificate template restored successfully'
        ]);
    }

    public function deleteArchived($id)
    {
        $archivedSeminar = ArchiveSeminar::findOrFail($id);
        $archivedSeminar->delete();

        return response()->json([
            'message' => 'Archived seminar permanently deleted'
        ]);
    }

    public function deleteArchivedCertTemplate($id)
    {
        $archivedTemplate = ArchivedCertificateTemplate::findOrFail($id);
        $archivedTemplate->delete();

        return response()->json([
            'message' => 'Archived certificate template permanently deleted'
        ]);
    }
}
