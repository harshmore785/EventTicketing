<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Events\StoreEventRequest;
use App\Http\Requests\Events\UpdateEventRequest;
use App\Models\Event;
use App\Models\TicketAvailability;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\EventManagement;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    protected $eventRepository;
    public function __construct()
    {
        $this->eventRepository = new EventManagement;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        
        $events = Event::when(
            $authUser->hasRole('Organizer'),
            function ($query) use ($authUser) {
                $query->where('created_by', $authUser->id);
            }
        )
        ->latest()
        ->get();
        

        return view('events.event-list')->with(['events' => $events]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ticketTypes = TicketType::latest()->get();
        return view('events.add-event')->with(['ticketTypes' => $ticketTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {

        try {
            $this->eventRepository->store($request->validated());
            return response()->json(['success' => 'Event created successfully!']);
        } catch (Exception $e) {
            return $this->respondWithAjax($e, 'adding', 'Event');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if ($event) {
            $ticketTypes = TicketType::with('ticketAvailabilities')->latest()->get();

            $eventId = $event->id;

            $ticketTypes = TicketType::with(['ticketAvailabilities' => function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            }])->latest()->get();

            $TicketType = $ticketTypes->map(function ($type) {
                $availability = $type->ticketAvailabilities->first();
                return [
                    'ticket_type_id'    => $type->id,
                    'ticket_type_name'  => $type->name,
                    'total_tickets'     => $availability->total_tickets ?? 0,
                    'sold_tickets'      => $availability->sold_tickets ?? 0,
                    'available_tickets' => $availability->available_tickets ?? 0,
                    'price'             => $availability->price ?? 0,
                ];
            });

            return view('events.show')->with([
                'event'       => $event,
                'ticketTypes' => $TicketType,
            ]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event) {
            $data = $this->eventRepository->editEvent($event);

            return view('events.edit-event', $data);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {

        try {
            $this->eventRepository->updateEvent($request->validated(), $event);
            return response()->json(['success' => 'Event updated successfully!']);
        } catch (Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'Event');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try
        {
            DB::beginTransaction();
            TicketAvailability::where('event_id', $event->id)->delete();
            $event->delete();
            DB::commit();

            return response()->json(['success'=> 'Event deleted successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'deleting', 'Event');
        }
    }
}
