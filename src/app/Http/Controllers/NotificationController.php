<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Http\Resources\NotificationResource;
use App\Models\Proxy;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationHelper $helper) {

    }

    /**
     * @OA\Get(
     *     path="/api/notifications/{id}",
     *     summary="Уведомления",
     *     tags={"Notifications"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request, Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $data = $this->helper->paginate($proxy);

        return NotificationResource::collection($data);
    }
}
