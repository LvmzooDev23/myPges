<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreInternshipReportRequest;
use App\Http\Requests\Report\ValidateReportRequest;
use App\Http\Resources\InternshipReportResource;
use App\Models\Application;
use App\Models\InternshipReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InternshipReportController extends Controller
{
    public function store(StoreInternshipReportRequest $request, Application $application): JsonResponse
    {
        $student = auth('api')->user()->student;
        $this->authorize('uploadReport', $application);

        if ((int) $application->student_id !== (int) $student->id) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        if ($application->status !== 'accepted') {
            return response()->json(['message' => 'Stage non accepté.'], 422);
        }

        if ($application->report) {
            return response()->json(['message' => 'Rapport déjà déposé.'], 422);
        }

        $file = $request->file('file');
        $path = $file->storeAs(
            'reports/'.$application->id,
            Str::uuid()->toString().'.'.$file->getClientOriginalExtension(),
            'private'
        );

        $supervisorId = $student->supervisor_id;

        $report = InternshipReport::create([
            'application_id' => $application->id,
            'student_id' => $student->id,
            'supervisor_id' => $supervisorId,
            'file_path' => $path,
            'title' => $request->input('title'),
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return response()->json(new InternshipReportResource($report->load('supervisor')), 201);
    }

    public function validateReport(ValidateReportRequest $request, InternshipReport $report): JsonResponse
    {
        $this->authorize('validateReport', $report);

        $report->update([
            'status' => $request->input('status'),
            'feedback' => $request->input('feedback'),
            'validated_at' => $request->input('status') === 'validated' ? now() : null,
        ]);

        return response()->json(new InternshipReportResource($report->fresh()->load('supervisor')));
    }

    public function download(InternshipReport $report): StreamedResponse|JsonResponse
    {
        $this->authorize('view', $report);

        if (! Storage::disk('private')->exists($report->file_path)) {
            return response()->json(['message' => 'Fichier introuvable.'], 404);
        }

        return Storage::disk('private')->response($report->file_path);
    }
}
