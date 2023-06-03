<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Status;
use App\Events\ServiceCreated;
use App\Events\ServiceUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ServiceController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $services = Service::latest()->get();
        return $this->json(200, null, ServiceResource::collection($services));
    }

    /**
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        $service = Service::create([
            ...$request->only('customer_name'),
            'origin_coordinate' => new Point(...$request->input('origin_coordinate')),
            'destination_coordinate' => new Point(...$request->input('destination_coordinate')),
            'status' => 'ready_to_send'
        ]);
        ServiceCreated::dispatch($service);
        return $this->json(201, 'service created successfully', ServiceResource::make($service));
    }

    /**
     * @param Service $service
     * @return JsonResponse
     */
    public function updateStatus(Service $service): JsonResponse
    {
        $service->update([
            'status' =>  Status::next($service->status)->value
        ]);

        event(new ServiceUpdated($service));

        return $this->json(200, 'service updated successfully', ServiceResource::make($service));
    }
}
