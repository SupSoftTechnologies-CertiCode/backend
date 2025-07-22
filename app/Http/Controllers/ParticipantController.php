<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\User;
use App\Models\Guest;
use App\Models\Seminar;
use App\Models\Transaction;
use App\Models\CertificateTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use App\Mail\CertificateMail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class ParticipantController extends Controller
{
    public function generateCertificate(Request $request, $participantId)
    {
        try {
            // 🔹 Fetch participant details
            $participant = Participant::findOrFail($participantId);

            // 🔹 Determine recipient details
            if ($participant->user) {
                $email = $participant->user->email;
                $name = $participant->user->name;
            } elseif ($participant->guest) {
                $email = $participant->guest->email;
                $name = $participant->guest->name;
            } else {
                return response()->json(['error' => 'No associated user or guest'], 400);
            }

            // 🔹 Ensure the 'generated' directory exists in storage
            $generatedDir = Storage::path('generated');
            if (!File::exists($generatedDir)) {
                File::makeDirectory($generatedDir, 0755, true);
            }

            // 🔹 Define the generated certificate file path
            $certificateFileName = "certificate_" . trim($participantId) . ".pdf";
            $certificatePath = Storage::path("generated/{$certificateFileName}");

            // 🔹 Check if we should use the HTML-generated certificate from the frontend
            if ($request->isMethod('post') && $request->has('use_html_method') && $request->input('use_html_method') && $request->has('pdf_blob')) {
                // Use the PDF blob sent from the frontend
                $pdfBlob = base64_decode($request->input('pdf_blob'));

                // Save the PDF blob to the file system
                file_put_contents($certificatePath, $pdfBlob);

                Log::info("Using HTML-generated certificate for participant {$participantId}");
            } else {
                // 🔹 Fetch assigned certificate template for backend generation
                $template = CertificateTemplate::find($participant->seminar->certificate_template_id);

                if (!$template) {
                    return response()->json(['error' => 'Certificate template not found'], 404);
                }

                // 🔹 Get the path of the stored PDF template (from Storage)
                $templatePath = Storage::disk('public')->path($template->pdf_filename);
                Log::info($templatePath);
                if (!File::exists($templatePath)) {
                    return response()->json(['error' => 'Template file not found'], 404);
                }

                // 🔹 Generate Certificate using FPDI (backend method)
                $pdf = new Fpdi();
                $pdf->AddPage('L', 'A4');
                $pdf->setSourceFile($templatePath);
                $tplIdx = $pdf->importPage(1);
                $pdf->useTemplate($tplIdx);

                // 🔹 Set Larger Font for Name
                $pdf->SetFont('Times', 'BI', 50);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(20, 110);
                $pdf->Cell(0, 10, $name, 0, 1, 'C');

                // 🔹 Set Normal Font for Other Details
                $pdf->SetFont('Arial', '', 16);
                $pdf->SetXY(138, 130);
                $pdf->Cell(0, 10, $participant->seminar->name_of_seminar, 0, 1, 'C');

                $pdf->SetXY(98, 143);
                $pdf->Cell(0, 10, $participant->seminar->topics, 0, 1, 'L');

                $pdf->SetXY(0, 158);
                $pdf->Cell(0, 10, $participant->seminar->location, 0, 1, 'C');

                $pdf->SetXY(45, 179);
                $pdf->Cell(0, 10, date('F d, Y', strtotime($participant->seminar->date)), 0, 1, 'L');

                $pdf->SetXY(199, 191);
                $pdf->Cell(45, 5, $participant->seminar->speaker_name, 0, 1, 'C');

                // 🔹 Save the generated certificate in Storage
                $pdf->Output($certificatePath, 'F');

                Log::info("Using backend-generated certificate for participant {$participantId}");
            }

            // 🔹 Prepare email data
            $data = [
                'user_name' => $name,
                'seminar_name' => $participant->seminar->name_of_seminar,
                'seminar_topics' => $participant->seminar->topics,
                'seminar_location' => $participant->seminar->location,
                'seminar_speaker' => $participant->seminar->speaker_name,
                'seminar_date' => date('F d, Y', strtotime($participant->seminar->date)),
                'certificate_path' => $certificatePath,
            ];

            // 🔹 Send email with certificate attachment
            Mail::to($email)->send(new CertificateMail($data));

            return response()->json(['message' => 'Certificate sent successfully to ' . $email]);
        } catch (\Exception $e) {
            Log::error('Certificate generation error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send certificate: ' . $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required|exists:seminars,id',
            'guest_id' => 'nullable|exists:guests,id',
            'user_id' => 'nullable|exists:users,id',
            'transaction_id' => 'nullable|exists:transactions,id', // Accepts transaction_id
        ]);

        // Determine if the seminar is free (i.e., no payment required)
        $isFreeSeminar = $request->input('payment_status') === 'completed' || $request->input('payment_status') === 'free';

        // Create the participant record
        $participant = Participant::create([
            'seminar_id' => $request->seminar_id,
            'user_id' => $request->user_id ?? null,
            'guest_id' => $request->guest_id ?? null,
        ]);

        // **If it's a paid seminar, update the transaction**
        if (!$isFreeSeminar && $request->transaction_id) {
            $transaction = Transaction::find($request->transaction_id);
            if ($transaction) {
                $transaction->participant_id = $participant->id;
                $transaction->save();
            }
        }

        return response()->json([
            'message' => 'Successfully joined the seminar!',
            'participant' => $participant,
        ], 201);
    }




    public function index()
    {
        $participants = Participant::with(['user', 'guest', 'seminar'])->get()->makeHidden(['seminar_id', 'user_id', 'guest_id']);
        return response()->json($participants);
    }

    public function show($id)
    {
        $participant = Participant::with(['user', 'guest', 'seminar'])->findOrFail($id);
        return response()->json($participant->makeHidden(['seminar_id', 'user_id', 'guest_id']));
    }
}
