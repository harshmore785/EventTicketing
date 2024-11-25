<x-admin.layout>
    <x-slot name="title">Edit Event</x-slot>
    <x-slot name="heading">Edit Event</x-slot>


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
                                    <input class="form-control" id="title" name="title" type="text" placeholder="Enter Event Title" value="{{ $event->title }}">
                                    <span class="text-danger is-invalid title_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input class="form-control" id="date" name="date" type="date" placeholder="Enter Event Date" min="{{ date('Y-m-d')  }}" value="{{ $event->date }}">
                                    <span class="text-danger is-invalid date_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="location">Location <span class="text-danger">*</span></label>
                                    <input class="form-control" id="location" name="location" type="text" placeholder="Enter Event location" value="{{ $event->location }}">
                                    <span class="text-danger is-invalid location_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description"> {{$event->description  }}</textarea>
                                    <span class="text-danger is-invalid description_err"></span>
                                </div>
                            </div>
                            <hr>
                            <h4 class="card-title">Edit Ticket Availabilty</h4>

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
                                                <input type="hidden" class="form-control" name="ticket_type_id[]" value="{{ $ticketType['ticket_type_id'] }}">
                                                {{ $ticketType['ticket_type_name'] }}
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="total_tickets[]" value="{{ $ticketType['total_tickets'] }}">
                                                <span class="text-danger is-invalid total_tickets_err"></span>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="sold_tickets[]" readonly value="{{ $ticketType['sold_tickets'] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="available_tickets[]" readonly value="{{ $ticketType['available_tickets'] }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="price[]" value="{{ $ticketType['price'] }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="addSubmit">Submit</button>
                            <a href="{{ route('events.index') }}" type="button" class="btn btn-warning">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


</x-admin.layout>


<!-- Update -->
<script>
    $(document).ready(function() {
        $("#editForm").submit(function(e) {
            e.preventDefault();
            $("#editSubmit").prop('disabled', true);
            var formdata = new FormData(this);
            formdata.append('_method', 'PUT');
            var model_id = $('#edit_model_id').val();
            var url = "{{ route('events.update', ":model_id") }}";
            //
            $.ajax({
                url: url.replace(':model_id', model_id),
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(data)
                {
                    $("#editSubmit").prop('disabled', false);
                    if (!data.error2)
                        swal("Successful!", data.success, "success")
                            .then((action) => {
                                window.location.href = '{{ route('events.index') }}';
                            });
                    else
                        swal("Error!", data.error2, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        $("#editSubmit").prop('disabled', false);
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        $("#editSubmit").prop('disabled', false);
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                }
            });

        });
    });
</script>
