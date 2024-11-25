<?php

namespace App\Repositories;

use App\Models\Event;
use App\Models\Purchase;
use App\Models\TicketAvailability;
use App\Models\TicketType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventManagement
{

    public function store($input)
    {
        DB::beginTransaction();
            $event = Event::create( Arr::only( $input, Event::getFillables() ) );

            // Store ticket availability data
            $ticketData = [];
            foreach ($input['ticket_type_id'] as $index => $ticketTypeId) {
                $ticketData[] = [
                    'event_id'      => $event->id,
                    'ticket_type_id'=> $ticketTypeId,
                    'total_tickets' => $input['total_tickets'][$index],
                    'sold_tickets'      => $input['sold_tickets'][$index] ?? 0,
                    'available_tickets' => $input['total_tickets'][$index],
                    'price'         => $input['price'][$index],
                ];
            }

        // Insert ticket data
        TicketAvailability::insert($ticketData);
        DB::commit();
    }

    public function editEvent($event)
    {
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

        return [
            'event'       => $event,
            'ticketTypes' => $TicketType,
        ];
    }

    public function updateEvent($input, $event)
    {
        DB::beginTransaction();
        $event->update(Arr::only($input, Event::getFillables()));

        if (!empty($input['ticket_type_id'])) {
            foreach ($input['ticket_type_id'] as $index => $ticketTypeId) {
                $ticketAvailability = TicketAvailability::where('event_id', $event->id)
                    ->where('ticket_type_id', $ticketTypeId)
                    ->first();

                if ($ticketAvailability) {
                    $ticketAvailability->update([
                        'total_tickets' => $input['total_tickets'][$index],
                        'available_tickets' => $input['total_tickets'][$index] - $input['sold_tickets'][$index],
                        'price' => $input['price'][$index],
                    ]);
                } else {
                    TicketAvailability::create([
                        'event_id' => $event->id,
                        'ticket_type_id' => $ticketTypeId,
                        'total_tickets' => $input['total_tickets'][$index],
                        'sold_tickets' => 0,
                        'price' => $input['price'][$index],
                    ]);
                }
            }
        }

        DB::commit();
    }

    public function showEvent($event)
    {
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

        // Attendee Details
        $attendee = Purchase::with('event','user','ticketType')->where('event_id',$eventId)->get();

        return [
            'event'       => $event,
            'ticketTypes' => $TicketType,
            'attendee'    => $attendee,
        ];
    }

}
