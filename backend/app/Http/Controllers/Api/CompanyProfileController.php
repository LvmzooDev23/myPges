<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\UpdateCompanyProfileRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;

class CompanyProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $company = auth('api')->user()->company->load('user');

        return response()->json(new CompanyResource($company));
    }

    public function update(UpdateCompanyProfileRequest $request): JsonResponse
    {
        $company = auth('api')->user()->company;
        $this->authorize('update', $company);
        $company->update($request->validated());

        return response()->json(new CompanyResource($company->fresh()->load('user')));
    }
}
