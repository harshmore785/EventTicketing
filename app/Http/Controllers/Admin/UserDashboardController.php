<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TicketPurchaseMail;
use App\Models\Event;
use App\Models\Purchase;
use App\Models\Question;
use App\Models\TicketAvailability;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserDashboardController extends Controller
{
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'event_id'       => 'required|exists:events,id',
            'user_id'        => 'required|exists:users,id',
            'quantity'       => 'required|integer|min:1',
        ]);

        try {
            // Retrieve ticket availability
            $ticketAvailability = TicketAvailability::with('ticketTypes')
                ->where('event_id', $validatedData['event_id'])
                ->where('ticket_type_id', $validatedData['ticket_type_id'])
                ->first();

            if (!$ticketAvailability) {
                return response()->json(['error' => 'Ticket availability not found.'], 404);
            }

            // Check ticket availability
            if ($ticketAvailability->available_tickets < $validatedData['quantity']) {
                return response()->json(['error' => 'Not enough tickets available.'], 400);
            }

            // Update ticket availability
            $ticketAvailability->available_tickets -= $validatedData['quantity'];
            $ticketAvailability->sold_tickets += $validatedData['quantity'];
            $ticketAvailability->save();

            // Calculate total price
            $totalPrice = $ticketAvailability->price * $validatedData['quantity'];

            // Start fake payment process
            $paymentResult = $this->processPayment($validatedData['user_id'], $totalPrice);

            if (!$paymentResult['success']) {
                return response()->json(['error' => $paymentResult['message']], 400);
            }

            // Store the purchase
            Purchase::create([
                'user_id'        => $validatedData['user_id'],
                'ticket_type_id' => $validatedData['ticket_type_id'],
                'event_id'       => $validatedData['event_id'],
                'quantity'       => $validatedData['quantity'],
                'total_price'    => $totalPrice,
                'payment_status' => $paymentResult['success'] ? Purchase::PAYMENT_SUCCESS : Purchase::PAYMENT_FAILED,
                'transaction_id' => $paymentResult['transaction_id'] ?? null,
            ]);

            // Get event details
            $event = Event::findOrFail($validatedData['event_id']);

            // Prepare ticket details for email
            $ticketDetails = [
                'user_name'         => auth()->user()->name ?? 'User',
                'ticket_type_name'  => $ticketAvailability->ticketTypes->name ?? 'Unknown',
                'quantity'          => $validatedData['quantity'],
                'total_price'       => $totalPrice,
                'event_name'        => $event->title,
                'event_date'        => $event->date,
            ];

            // Send email to the user
            Mail::to(auth()->user()->email)->send(new TicketPurchaseMail($ticketDetails));

            return response()->json(['success' => 'Ticket purchased successfully!'], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in ticket purchase: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    private function processPayment($userId, $amount)
    {
        if ($amount < 100) {
            return [
                'success' => false,
                'message' => 'Payment failed due to insufficient balance.',
            ];
        }

        return [
            'success' => true,
            'transaction_id' => uniqid('txn_'),
        ];
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
