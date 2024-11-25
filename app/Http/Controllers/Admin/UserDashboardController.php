<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Mail\TicketPurchaseMail;
use App\Models\Event;
use App\Models\Purchase;
use App\Models\Question;
use App\Models\TicketAvailability;
use App\Models\TicketType;
use App\Repositories\EventBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserDashboardController extends Controller
{

    protected $eventBooking;
    public function __construct()
    {
        $this->eventBooking = new EventBooking;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $location = $request->location;
            $date = $request->date;

            $event_details = Event::query();

            if (!empty($location)) {
                $event_details->where('location', 'like', '%' . $location . '%');
            }

            if (!empty($date)) {
                $event_details->whereDate('date', $date);
            }else{
                $event_details->whereDate('date','>=', date('Y-m-d'));
            }

            $event_details = $event_details->where('status', 1)->get();

            return view('user.dashboard')->with([
                'event_details' => $event_details,
                'location' => $location,
                'date' => $date
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing the request.'
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        try {
            $this->eventBooking->store($request->validated());
            return response()->json(['success' => 'Purchase created successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function addQuestion(Request $request)
    {
        $validatedData = $request->validate([
            'event_id'    => 'required|exists:events,id',
            'user_id'     => 'required|exists:users,id',
            'subject'     => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        try {
            Question::create([
                'user_id'     => $validatedData['user_id'],
                'event_id'    => $validatedData['event_id'],
                'subject'     => $validatedData['subject'],
                'description' => $validatedData['description'],
            ]);

            return response()->json(['success' => 'Question added successfully!'], 200);
        } catch (\Exception $e) {
            \Log::error('Error adding question: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your request. Please try again.',
            ], 500);
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
