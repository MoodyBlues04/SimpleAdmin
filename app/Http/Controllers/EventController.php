<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Models\User;
use App\Response\ResponseHandler;
use Illuminate\Http\JsonResponse;

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
        try {
            $event->load(['creator', 'joinedUsers']);
            return ResponseHandler::sendResponse($event->toArray());
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    public function store(EventStoreRequest $request): JsonResponse
    {
        $event = $this->authUser()->createdEvents()->create([
            'header' => $request->header,
            'text' => $request->text,
            'creator_id' => $this->authUser()->id,
        ]);

        return ResponseHandler::sendResponse(['id' => $event->id]);
    }

    public function update(EventUpdateRequest $request, Event $event): JsonResponse
    {
        try {
            $this->authorize('update', $event);

            $event->header = $request->header ?? $event->header;
            $event->text = $request->text ?? $event->text;
            if (!$event->save()) {
                throw new \Exception("Not updated");
            }

            return ResponseHandler::sendResponse(['id' => $event->id]);
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    public function destroy(Event $event): JsonResponse
    {
        try {
            $this->authorize('delete', $event);

            if (!$event->delete()) {
                throw new \Exception("Not deleted");
            }

            return ResponseHandler::sendResponse("Deleted");
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    public function join(Event $event): JsonResponse
    {
        try {
            if ($this->authUser()->joinedEvents()->get()->contains($event)) {
                throw new \Exception("You already participate in event {$event->id}");
            }

            $this->authUser()->joinedEvents()->attach($event);
            return ResponseHandler::sendResponse("Joined event {$event->id}");
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    public function cancel(Event $event): JsonResponse
    {
        try {
            if (!$this->authUser()->joinedEvents()->get()->contains($event)) {
                throw new \Exception("You are not participate in event {$event->id}");
            }

            $this->authUser()->joinedEvents()->detach($event);
            return ResponseHandler::sendResponse("Cancelled event {$event->id}");
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    private function authUser(): User
    {
        return auth()->user();
    }
}
