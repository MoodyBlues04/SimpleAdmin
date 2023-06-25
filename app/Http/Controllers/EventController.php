<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
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

        $event = $this->authUser()->createdEvents()->create([
            'header' => $request->header,
            'text' => $request->text,
            'creator_id' => $this->authUser()->id,
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

    public function join(Event $event): JsonResponse
    {
        if ($this->authUser()->joinedEvents()->get()->contains($event)) {
            return response()->json(['ok' => false, ['result' => "You already participate in event {$event->id}"]]);
        }

        $this->authUser()->joinedEvents()->attach($event);
        return response()->json(['ok' => true, ['result' => "Joined event {$event->id}"]]);
    }

    public function cancel(Event $event): JsonResponse
    {
        if (!$this->authUser()->joinedEvents()->get()->contains($event)) {
            return response()->json(['ok' => false, ['result' => "You are not participate in event {$event->id}"]]);
        }

        $this->authUser()->joinedEvents()->detach($event);
        return response()->json(['ok' => true, ['result' => "Cancelled event {$event->id}"]]);
    }

    private function authUser(): User
    {
        return auth()->user();
    }
}
