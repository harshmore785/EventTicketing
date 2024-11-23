<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventRequest;
use App\Models\Event;
use App\Models\TicketAvailability;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events.event-list');
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
        try
        {
            DB::beginTransaction();
            $input = $request->validated();
            $event = Event::create( Arr::only( $input, Event::getFillables() ) );

            // Store ticket availability data
            $ticketData = [];
            foreach ($input['ticket_type_id'] as $index => $ticketTypeId) {
                $ticketData[] = [
                    'event_id'      => $event->id,
                    'ticket_type_id'=> $ticketTypeId,
                    'total_tickets' => $input['total_tickets'][$index],
                    'sold_tickets'  => $input['sold_tickets'][$index] ?? 0,
                    'price'         => $input['price'][$index],
                ];
            }

        // Insert ticket data
        TicketAvailability::insert($ticketData);
        DB::commit();

        return response()->json(['success'=> 'Office created successfully!']);
        }
        catch(\Exception $e)
        {
            return $this->respondWithAjax($e, 'creating', 'Office');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
