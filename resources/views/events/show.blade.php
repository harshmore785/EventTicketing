@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Crypt;
@endphp
<x-admin.layout>
    <x-slot name="title">Details of Event</x-slot>
    <x-slot name="heading">Details of Event</x-slot>


        <!-- Add Form -->
        <div class="row" id="addContainer">
            <div class="col-sm-12">
                <div class="card">
                    <form class="theme-form" name="editForm" id="editForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_model_id" name="edit_model_id" value="{{ $event->id }}">
                        <div class="card-header">
                            <h4 class="card-title">Edit Event</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="title">Event Title <span class="text-danger">*</span></label>
                                    <input class="form-control" id="title" name="title" type="text" placeholder="Enter Event Title" value="{{ $event->title }}" readonly>
                                    <span class="text-danger is-invalid title_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input class="form-control" id="date" name="date" type="date" placeholder="Enter Event Date" min="{{ date('Y-m-d')  }}" value="{{ $event->date }}" readonly>
                                    <span class="text-danger is-invalid date_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="location">Location <span class="text-danger">*</span></label>
                                    <input class="form-control" id="location" name="location" type="text" placeholder="Enter Event location" value="{{ $event->location }}" readonly>
                                    <span class="text-danger is-invalid location_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description" readonly> {{$event->description  }}</textarea>
                                    <span class="text-danger is-invalid description_err"></span>
                                </div>
                            </div>
                            <hr>
                            <h4 class="card-title">Show Ticket Availabilty</h4>
                            @if (Auth::user()->hasrole('Organizer'))
                            <table class="table table-borderd">
                                <thead>
                                    <tr>
                                        <th>Ticket Type</th>
                                        <th>Total Tickets</th>
                                        <th>Sold Tickets</th>
                                        <th>Available Tickets</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticketTypes as $ticketType)
                                        <tr>
                                            <td>
                                                <input type="hidden" class="form-control" name="ticket_type_id[]" value="{{ $ticketType['ticket_type_id'] }}" readonly>
                                                {{ $ticketType['ticket_type_name'] }}
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="total_tickets[]" value="{{ $ticketType['total_tickets'] }}" readonly>
                                                <span class="text-danger is-invalid total_tickets_err"></span>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="sold_tickets[]" readonly value="{{ $ticketType['sold_tickets'] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="available_tickets[]" readonly value="{{ $ticketType['available_tickets'] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="price[]" value="{{ $ticketType['price'] }}" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <h4 class="card-title">Attendee Details</h4>
                            <table class="table table-borderd">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Ticket Type</th>
                                        <th>Total Tickets</th>
                                        <th>Price</th>
                                        <th>Transaction ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendee as $attendee)
                                        <tr>
                                            <td>
                                                {{ Crypt::decryptstring($attendee?->user?->name) }}
                                            </td>
                                            <td>
                                                {{ $attendee?->ticketType?->name }}
                                            </td>
                                            <td>
                                                {{ $attendee?->quantity }}
                                            </td>
                                            <td>
                                                {{ $attendee?->total_price }}
                                            </td>
                                            <td>
                                                {{ $attendee?->transaction_id }}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                           @else
                            <div class="row">
                                @foreach ($ticketTypes as $ticketType)
                                    <div class="col-xl-4 col-md-4">
                                        <div class="card card-animate overflow-hidden" id="purchaseTicket" data-id="{{ $ticketType['ticket_type_id'] }}">
                                            <div class="card-body bg-primary-subtle" style="z-index:1;">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="text-uppercase fw-semibold text-muted text-truncate mb-3">
                                                            {{ $ticketType['ticket_type_name'] }}
                                                        </p>
                                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                                            Rs. <span class="counter-value" data-target="{{ $ticketType['price'] }}">{{ $ticketType['price'] }}</span>/-
                                                        </h4>
                                                        <h6 class="fw-semibold ff-secondary mb-0">
                                                            {{ $ticketType['total_tickets'] . "/" . $ticketType['available_tickets'] }}
                                                            <br><br>Click here for purchase
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                        @if (Auth::user()->hasrole('Organizer'))
                        <div class="card-footer">
                            <a href="{{ route('events.index') }}" type="button" class="btn btn-warning">Cancel</a>
                        </div>
                        @else
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="addQuestion" data-id="{{ $event->id }}">Have any Question ?</button>
                            <a href="{{ route('user-dashboard.index') }}" type="button" class="btn btn-warning">Cancel</a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>


        {{-- purchase Ticket Form --}}
        <div class="modal fade" id="purchase-modal" tabindex="-1" role="dialog" aria-labelledby="purchaseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="purchaseModalLabel">Purchase Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="purchase-form">
                            <input type="hidden" name="ticket_type_id" id="ticket_type_id">
                            <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}" >
                            <div class="form-group">
                                <label for="quantity">Number of Tickets</label>
                                <input type="number" class="form-control" name="quantity" id="quantity" min="1" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Question or Comment Form --}}
        <div class="modal fade" id="question-modal" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="questionModalLabel">Add Question or Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="question-form">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="question_description">Description</label>
                                <textarea name="question_description" id="question_description" class="form-control" required></textarea>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</x-admin.layout>


<!-- Update -->
<script>
   $(document).on("click", "#addQuestion", function (e) {
        e.preventDefault();
        var eventId = $(this).data("id");
        $("#question-modal").modal("show");
        $("#event_id").val(eventId);
    });

$("#question-form").on("submit", function (e) {
    e.preventDefault();
    var subject = $("#subject").val();
    var description = $("#question_description").val();
    var eventId = $("#event_id").val();

    $.ajax({
        url: '{{ route('add-question') }}',
        method: "POST",
        data: {
            subject: subject,
            description: description,
            event_id: eventId, // Use eventId variable
            user_id: "{{ auth()->id() }}",
            _token: "{{ csrf_token() }}",
        },
        success: function (data) {
            if (!data.error && !data.error2) {
                swal("Success!", data.success, "success").then(() => {
                    window.location.href = '{{ route('user-dashboard.index') }}';
                });
            } else {
                swal("Error!", data.error || data.error2, "error");
            }
        },
        error: function () {
            swal("Error!", "Something went wrong", "error");
        },
    });
});


     $(document).on("click", "#purchaseTicket", function (e) {
        e.preventDefault();
        var ticketTypeId = $(this).data("id");
        $("#ticket_type_id").val(ticketTypeId);
        $("#purchase-modal").modal("show");
    });

    $("#purchase-form").on("submit", function (e) {
        e.preventDefault();

        var ticketTypeId = $("#ticket_type_id").val();
        var quantity = $("#quantity").val();
        var event_id = $("#event_id").val();
        // alert(event_id);
        $.ajax({
            url: '{{ route('user-dashboard.store') }}',
            method: "POST",
            data: {
                ticket_type_id: ticketTypeId,
                quantity: quantity,
                event_id: event_id,
                user_id: "{{ auth()->id() }}",
                _token: "{{ csrf_token() }}", // Include CSRF token
            },
            success: function(data, textStatus, jqXHR) {
            if (!data.error && !data.error2) {
                swal("Success!", data.success, "success")
                    .then((action) => {
                        window.location.href = '{{ route('user-dashboard.index') }}';
                    });
            } else {
                if (data.error) {
                    swal("Error!", data.error, "error");
                } else {
                    swal("Error!", data.error2, "error");
                }
            }
        },
        error: function(error, jqXHR, textStatus, errorThrown) {
            swal("Error!", "Something went wrong", "error");
        },
        });
    });

</script>
