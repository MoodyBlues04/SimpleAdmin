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
        $request->validate([
            'header' => 'required|string|max:30',
            'text' => 'required|string|max:150'
        ]);

        $event = auth()->user()->events()->create([
            'header' => $request->header,
            'text' => $request->text,
            'creator_id' => auth()->user()->id,
        ]);

        return response()->json(['ok' => true, 'result' => ['id' => $event->id]]);
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        $this->authorize('update', $event);

        $request->validate([
            'header' => 'nullable|string|max:30',
            'text' => 'nullable|string|max:150',
        ]);

        $event->header = $request->header ?? $event->header; // TODO to request getUpdateArray
        $event->text = $request->text ?? $event->text;
        if (!$event->save()) {
            return response()->json(['ok' => false, 'result' => ['message' => 'Not saved']]);
        }

        return response()->json(['ok' => true, 'result' => ['id' => $event->id]]);
    }

    public function destroy(Event $event): JsonResponse
    {
        $this->authorize('delete', $event);

        if (!$event->delete()) {
            return response()->json(['ok' => false, 'result' => ['message' => 'Not deleted']]);
        }

        return response()->json(['ok' => true, 'result' => ['message' => 'Deleted']]);
    }
}
