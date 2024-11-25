<x-admin.layout>
    <x-slot name="title">Add Event</x-slot>
    <x-slot name="heading">Add Event</x-slot>


        <!-- Add Form -->
        <div class="row" id="addContainer">
            <div class="col-sm-12">
                <div class="card">
                    <form class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                        @csrf

                        <div class="card-header">
                            <h4 class="card-title">Add Event</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="col-md-4">
                                    <label class="col-form-label" for="title">Event Title <span class="text-danger">*</span></label>
                                    <input class="form-control" id="title" name="title" type="text" placeholder="Enter Event Title">
                                    <span class="text-danger is-invalid title_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input class="form-control" id="date" name="date" type="date" placeholder="Enter Event Date" min="{{ date('Y-m-d')  }}">
                                    <span class="text-danger is-invalid date_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="location">Location <span class="text-danger">*</span></label>
                                    <input class="form-control" id="location" name="location" type="text" placeholder="Enter Event location">
                                    <span class="text-danger is-invalid location_err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label" for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                    <span class="text-danger is-invalid description_err"></span>
                                </div>
                            </div>
                            <hr>
                            <h4 class="card-title">Add Ticket Availabilty</h4>

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
                                    @foreach ($ticketTypes as $key=>$ticketType)
                                        <tr>
                                            <td><input type="hidden" class="form-control" name="ticket_type_id[]" value="{{ $ticketType->id }}"> {{ $ticketType->name }}</td>
                                            <td>
                                                <input type="text" class="form-control" name="total_tickets[]">
                                                <span class="text-danger total_tickets[].err"></span> 
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="sold_tickets[]" readonly value="0">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="available_tickets[]" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="price[]">
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


{{-- Add --}}
<script>
    $("#addForm").submit(function(e) {
        e.preventDefault();
        $("#addSubmit").prop('disabled', true);

        var formdata = new FormData(this);
        $.ajax({
            url: '{{ route('events.store') }}',
            type: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: function(data)
            {
                $("#addSubmit").prop('disabled', false);
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
                    $("#addSubmit").prop('disabled', false);
                    resetErrors();
                    printErrMsg(responseObject.responseJSON.errors);
                },
                500: function(responseObject, textStatus, errorThrown) {
                    $("#addSubmit").prop('disabled', false);
                    swal("Error occured!", "Something went wrong please try again", "error");
                }
            }
        });

    });
</script>
