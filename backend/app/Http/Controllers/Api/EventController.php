<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->query('size', 10);
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');

        $querryFromDb = Event::query()->orderBy($sort, $order);

        if ($searchText = $request->query('search')) {
            $querryFromDb->where(function($query) use ($searchText) {
                $query->where('title', 'like', "%$searchText%")
                      ->orWhere('description', 'like', "%$searchText%")
                      ->orWhere('location', 'like', "%$searchText%")
                      ->orWhere('category', 'like', "%$searchText%");
            });
        }

        $events = $querryFromDb->paginate($size);

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $event = Event::create($data);
        return (new EventResource($event))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest  $request, Event $event)
    {
        $data = $request->validated();
        $event->update($data);
        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->noContent();
    }
}
