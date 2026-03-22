<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use App\Http\Resources\EvaluationResource;
use App\Models\Application;
use App\Models\Evaluation;
use Illuminate\Http\JsonResponse;

class EvaluationController extends Controller
{
    public function store(StoreEvaluationRequest $request): JsonResponse
    {
        $this->authorize('create', Evaluation::class);

        $supervisor = auth('api')->user()->supervisor;
        $application = Application::with('student')->findOrFail($request->input('application_id'));

        if ($application->status !== 'accepted') {
            return response()->json(['message' => 'Candidature non acceptée.'], 422);
        }

        if ((int) $application->student->supervisor_id !== (int) $supervisor->id) {
            return response()->json(['message' => 'Étudiant non assigné à ce superviseur.'], 403);
        }

        if ($application->evaluation) {
            return response()->json(['message' => 'Évaluation déjà existante.'], 422);
        }

        $evaluation = Evaluation::create([
            'application_id' => $application->id,
            'supervisor_id' => $supervisor->id,
            'student_id' => $application->student_id,
            'score' => $request->input('score'),
            'criteria' => $request->input('criteria'),
            'comments' => $request->input('comments'),
        ]);

        return response()->json(new EvaluationResource($evaluation->load(['supervisor.user', 'student.user'])), 201);
    }

    public function show(Evaluation $evaluation): JsonResponse
    {
        $this->authorize('view', $evaluation);

        return response()->json(new EvaluationResource($evaluation->load(['supervisor.user', 'student.user', 'application'])));
    }
}
