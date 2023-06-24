<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        return response()->json(Event::all());
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json($event->toArray());
    }

    public function store(Request $request): JsonResponse // TODO custom request
    {
        $event = auth()->user()->events()->create([
            'header' => $request->header,
            'text' => $request->text,
            'creator_id' => auth()->user()->id,
        ]);

        return response()->json(['ok' => true, 'result' => ['id' => $event->id]]);
    }
}
