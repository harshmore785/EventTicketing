<!DOCTYPE html>
<html>
<head>
    <title>Ticket Purchase Confirmation</title>
</head>
<body>
    <h1>Thank You for Your Purchase!</h1>
    <p>Dear {{ $ticketDetails['user_name'] }},</p>

    <p>Here are your ticket details:</p>
    <ul>
        <li>Event Name: {{ $ticketDetails['event_name'] }}</li>
        <li>Event Date: {{ $ticketDetails['event_date'] }}</li>
        <li>Ticket Type: {{ $ticketDetails['ticket_type_name'] }}</li>
        <li>Quantity: {{ $ticketDetails['quantity'] }}</li>
        <li>Total Price: Rs. {{ $ticketDetails['total_price'] }}</li>
    </ul>

    <p>We look forward to seeing you at the event!</p>
</body>
</html>
