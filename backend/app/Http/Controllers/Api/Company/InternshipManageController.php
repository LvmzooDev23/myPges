<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internship\StoreInternshipRequest;
use App\Http\Requests\Internship\UpdateInternshipRequest;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Repositories\Contracts\InternshipRepositoryInterface;
use Illuminate\Http\JsonResponse;

class InternshipManageController extends Controller
{
    public function __construct(
        protected InternshipRepositoryInterface $internships
    ) {}

    public function index(): JsonResponse
    {
        $company = auth('api')->user()->company;
        $list = $this->internships->forCompany($company->id);

        return response()->json(InternshipResource::collection($list));
    }

    public function show(Internship $internship): JsonResponse
    {
        $this->authorize('update', $internship);

        return response()->json(new InternshipResource($internship->load('company')));
    }

    public function store(StoreInternshipRequest $request): JsonResponse
    {
        $company = auth('api')->user()->company;
        $data = array_merge($request->validated(), ['company_id' => $company->id]);
        if (empty($data['status'])) {
            $data['status'] = 'draft';
        }
        $internship = $this->internships->create($data);

        return response()->json(new InternshipResource($internship->load('company')), 201);
    }

    public function update(UpdateInternshipRequest $request, Internship $internship): JsonResponse
    {
        $this->authorize('update', $internship);
        $internship = $this->internships->update($internship, $request->validated());

        return response()->json(new InternshipResource($internship->load('company')));
    }

    public function destroy(Internship $internship): JsonResponse
    {
        $this->authorize('delete', $internship);
        $this->internships->delete($internship);

        return response()->json(['message' => 'Stage supprimé.']);
    }
}
