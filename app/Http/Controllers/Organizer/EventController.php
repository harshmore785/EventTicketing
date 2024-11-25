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
            $data = $this->eventRepository->showEvent($event);

            return view('events.show', $data);
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

    // Change Loan Status
    public function changeStatus($model_id, $btn_status, Request $request)
    {
        try {
            $event = Event::find($model_id);
            $event->status = $btn_status;
            $event->updated_at = date('Y-m-d H:i:s');
            $event->save();
            return response()->json(['success' => 'Event Cancel successfully!']);
        } catch (\Exception $e) {
            return $this->respondWithAjax($e, 'updating', 'Event');
        }
    }
}
