<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
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

        $user = $request->user();

         $query = Booking::with([
            'user:id,name,email',
            'event',
        ])->orderBy($sort, $order);

        if ($user->hasRole('organizer')) {
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            });
        }

        $bookings = $query->paginate($size);

        return BookingResource::collection($bookings);
    }

    public function userBookings(Request $request)
    {
        $size = $request->query('size', 10);
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');

        $user = $request->user();

        $bookings = Booking::with([
            'user:id,name,email',   
            'event'
        ])
        ->where('user_id', $user->id)
        ->orderBy($sort, $order)
        ->paginate($size);

        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $data   = $request->validated();
        $userId = auth('api')->id();

        return DB::transaction(function () use ($data, $userId) {
            $event = Event::whereKey($data['event_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $alreadyBooked = Booking::where('event_id', $event->id)->sum('quantity');

            $available = max(0, $event->capacity - $alreadyBooked);

            if ($data['quantity'] > $available) {
                return response()->json([
                    'message'    => 'Not enough tickets left',
                    'available'  => $available,
                    'requested'  => (int) $data['quantity'],
                    'capacity'   => (int) $event->capacity,
                    'booked'     => (int) $alreadyBooked,
                ], 422);
            }

            $booking = Booking::create([
                'user_id'     => $userId,
                'event_id'    => $event->id,
                'quantity'    => (int) $data['quantity'],
                'total_price' => (int) $data['quantity'] * (int) $event->ticket_price,
            ]);

            return (new BookingResource($booking->load(['user', 'event'])))
                ->response()
                ->setStatusCode(201);
        });
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
