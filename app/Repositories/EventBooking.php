<?php
namespace App\Repositories;

use App\Models\TicketAvailability;
use App\Models\Purchase;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchaseMail;
use Illuminate\Support\Facades\Crypt;

class EventBooking
{
    public function store(array $input)
    {
        DB::beginTransaction();

        try {
            // Retrieve ticket availability
            $ticketAvailability = TicketAvailability::with('ticketTypes')
                ->where('event_id', $input['event_id'])
                ->where('ticket_type_id', $input['ticket_type_id'])
                ->firstOrFail();

            // Check ticket availability
            if ($ticketAvailability->available_tickets < $input['quantity']) {
                throw new \Exception('Not enough tickets available.', 400);
            }

            // Update ticket availability
            $ticketAvailability->available_tickets -= $input['quantity'];
            $ticketAvailability->sold_tickets += $input['quantity'];
            $ticketAvailability->save();

            // Calculate total price
            $totalPrice = $ticketAvailability->price * $input['quantity'];

            // Process payment
            $paymentResult = $this->processPayment($input['user_id'], $totalPrice);

            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message'], 400);
            }

            // Store the purchase
            $purchase = Purchase::create([
                'user_id'        => $input['user_id'],
                'ticket_type_id' => $input['ticket_type_id'],
                'event_id'       => $input['event_id'],
                'quantity'       => $input['quantity'],
                'total_price'    => $totalPrice,
                'payment_status' => Purchase::PAYMENT_SUCCESS,
                'transaction_id' => $paymentResult['transaction_id'],
            ]);

            // Send email
            $event = Event::findOrFail($input['event_id']);
            $ticketDetails = [
                'user_name'         => auth()->user()->name ?? 'User',
                'ticket_type_name'  => $ticketAvailability->ticketTypes->name ?? 'Unknown',
                'quantity'          => $input['quantity'],
                'total_price'       => $totalPrice,
                'event_name'        => $event->title,
                'event_date'        => $event->date,
            ];
            $useremail = Crypt::decryptstring(auth()->user()->email);
            Mail::to($useremail)->send(new TicketPurchaseMail($ticketDetails));

            DB::commit();

            return $purchase;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
}
