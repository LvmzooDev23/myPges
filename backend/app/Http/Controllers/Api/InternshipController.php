<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Repositories\Contracts\InternshipRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function __construct(
        protected InternshipRepositoryInterface $internships
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['q', 'location', 'type', 'from', 'to']);
        $paginator = $this->internships->paginatePublished($filters, (int) $request->get('per_page', 15));

        return InternshipResource::collection($paginator)->response();
    }

    public function show(int $id): JsonResponse
    {
        $internship = $this->internships->find($id);
        if (! $internship) {
            return response()->json(['message' => 'Stage introuvable.'], 404);
        }

        if ($internship->status !== 'published') {
            if (! auth('api')->check()) {
                return response()->json(['message' => 'Non autorisé.'], 401);
            }
            $this->authorize('view', $internship);
        }

        return response()->json(new InternshipResource($internship));
    }
}
