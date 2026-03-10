<?php

namespace App\Http\Controllers;

use App\Helpers\TelegramBotHelper;
use App\Http\Requests\TelegramBotFilterRequest;
use App\Http\Requests\TelegramBotRequest;
use App\Http\Resources\TelegramBotResource;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramBotController extends Controller
{
    public function __construct(private TelegramBotHelper $helper) {

    }

    public function index(TelegramBotFilterRequest $request)
    {
        $data = $this->helper->paginate(Auth::user(), $request->validated());

        return TelegramBotResource::collection($data);
    }

    public function store(TelegramBotRequest $request)
    {
        $this->helper->create(Auth::user(), $request->validated());

        return response()->json([
            'message' => 'Бот добавлен'
        ]);
    }

    public function toggle(Request $request, TelegramBot $bot)
    {
        $this->authorize('update', $bot);

        $this->helper->toggle($bot);

        return response()->json([
            'message' => 'Статус изменён'
        ]);
    }

    public function destroy(Request $request, TelegramBot $bot)
    {
        $this->authorize('delete', $bot);

        $this->helper->delete($bot);

        return response()->json([
            'message' => 'Бот удалён'
        ]);
    }
}