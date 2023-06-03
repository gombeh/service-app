<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $services = Service::latest()->get();
        return $this->json(200, null, ServiceResource::collection($services));
    }
}
