<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->query('size', 10);
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');

        $bookings = Booking::with([
            'user:id,name,email',   
            'event:id,title,starts_at'
        ])
        ->orderBy($sort, $order)
        ->paginate($size);

        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $booking = Booking::create($data);
        return (new BookingResource($booking))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'event']);
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $data = $request->validated();
        $booking->update($data);
        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->noContent();
    }
}
