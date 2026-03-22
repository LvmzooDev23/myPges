<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\ApplyApplicationRequest;
use App\Http\Requests\Application\UpdateApplicationStatusRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Internship;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function __construct(
        protected ApplicationRepositoryInterface $applications,
        protected NotificationService $notifications
    ) {}

    public function studentIndex(): JsonResponse
    {
        $student = auth('api')->user()->student;
        $list = $this->applications->forStudent($student->id);
        $list->load(['internship.company', 'report', 'evaluation']);

        return response()->json(ApplicationResource::collection($list));
    }

    public function apply(ApplyApplicationRequest $request, Internship $internship): JsonResponse
    {
        $this->authorize('apply', Application::class);

        if ($internship->status !== 'published') {
            return response()->json(['message' => 'Ce stage n\'accepte pas de candidatures.'], 422);
        }

        $company = $internship->company;
        if ($company->approval_status !== 'approved') {
            return response()->json(['message' => 'Entreprise non validée.'], 422);
        }

        $student = auth('api')->user()->student;

        $exists = Application::where('internship_id', $internship->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Candidature déjà envoyée.'], 422);
        }

        $application = $this->applications->create([
            'internship_id' => $internship->id,
            'student_id' => $student->id,
            'status' => 'pending',
            'cover_letter' => $request->input('cover_letter'),
            'applied_at' => now(),
        ]);

        $this->notifications->notify(
            $internship->company->user,
            'application_received',
            'Nouvelle candidature',
            'Une candidature a été reçue pour : '.$internship->title,
            ['application_id' => $application->id, 'internship_id' => $internship->id]
        );

        return response()->json(new ApplicationResource($application->load(['internship.company'])), 201);
    }

    public function companyInternshipApplications(Internship $internship): JsonResponse
    {
        $this->authorize('update', $internship);
        $list = $this->applications->forInternship($internship->id);

        return response()->json(ApplicationResource::collection($list));
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        $application->loadMissing('internship', 'student.user');
        $this->authorize('updateStatus', $application);

        $payload = $request->validated();

        DB::transaction(function () use ($application, $payload) {
            $application->update(['status' => $payload['status']]);

            if ($payload['status'] === 'accepted' && ! empty($payload['supervisor_id'])) {
                $application->student->update(['supervisor_id' => $payload['supervisor_id']]);
            }

            $this->notifications->notify(
                $application->student->user,
                'application_status',
                'Mise à jour candidature',
                'Votre candidature est : '.$payload['status'],
                ['application_id' => $application->id]
            );
        });

        return response()->json(new ApplicationResource($application->fresh()->load(['internship.company', 'student.user'])));
    }
}
