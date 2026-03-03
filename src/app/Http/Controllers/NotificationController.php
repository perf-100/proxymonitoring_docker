<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Proxy;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationHelper $helper) {

    }

    public function index(Request $request, $id)
    {
        $proxy = Proxy::findOrFail($id);
        $this->authorize('view', $proxy);

        $data = $this->helper->paginate($proxy);

        return response()->json(
            $data
        );
    }

}
