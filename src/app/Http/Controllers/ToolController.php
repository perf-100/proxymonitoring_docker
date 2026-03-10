<?php

namespace App\Http\Controllers;

use App\Helpers\ToolHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function __construct(private ToolHelper $helper) {

    }

    public function index(Request $request)
    {
        try {
            $data = $this->helper->lookup(Auth::user());

            return response()->json(
                $data
            );

        } catch (\Throwable $e) {

            return response()->json([
                'error' => 'Service unavailable'
            ], 503);
        }
    }
}