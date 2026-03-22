<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateStudentProfileRequest;
use App\Http\Requests\Student\UploadCvRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function profile(): JsonResponse
    {
        $student = auth('api')->user()->student->load('user', 'supervisor.user');

        return response()->json(new StudentResource($student));
    }

    public function update(UpdateStudentProfileRequest $request): JsonResponse
    {
        $student = auth('api')->user()->student;
        $this->authorize('update', $student);

        $data = $request->validated();

        DB::transaction(function () use ($student, $data) {
            $student->update($data);

            $user = $student->user;
            $first = $student->first_name ?? '';
            $last = $student->last_name ?? '';
            $combined = trim($first.' '.$last);
            if ($combined !== '') {
                $user->update(['name' => $combined]);
            }
        });

        return response()->json(new StudentResource($student->fresh()->load('user', 'supervisor')));
    }

    public function uploadCv(UploadCvRequest $request): JsonResponse
    {
        $student = auth('api')->user()->student;
        $this->authorize('update', $student);

        if ($student->cv_path) {
            Storage::disk('private')->delete($student->cv_path);
        }

        $file = $request->file('cv') ?? $request->file('file');
        $path = $file->storeAs(
            'cvs/'.$student->id,
            Str::uuid()->toString().'.'.$file->getClientOriginalExtension(),
            'private'
        );
        $student->update(['cv_path' => $path]);

        return response()->json([
            'message' => 'CV enregistré.',
            'student' => new StudentResource($student->fresh()->load('user')),
        ]);
    }

    public function downloadCv(): StreamedResponse|JsonResponse
    {
        $student = auth('api')->user()->student;
        $this->authorize('update', $student);

        if (! $student->cv_path || ! Storage::disk('private')->exists($student->cv_path)) {
            return response()->json(['message' => 'Aucun CV.'], 404);
        }

        return Storage::disk('private')->response($student->cv_path, 'cv.'.pathinfo($student->cv_path, PATHINFO_EXTENSION));
    }
}
