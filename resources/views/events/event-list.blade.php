<x-admin.layout>
    <x-slot name="title">Events</x-slot>
    <x-slot name="heading">Events</x-slot>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            @can('events.create')
                            <div class="col-sm-6">
                                <div class="">
                                    <a href="{{ route('events.create') }}" class="btn btn-primary">Add <i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $event->title }}</td>
                                            <td>{{ $event->date }}</td>
                                            <td>{{ $event->location }}</td>
                                            <td>{{ $event->description }}</td>
                                            <td>{{ ($event->status == 1) ? 'Active':''  }}</td>
                                            <td>
                                                @can('events.edit')
                                                    <a href="{{ route('events.edit',$event->id) }}" class="edit-element btn btn-secondary px-2 py-1" title="Edit Employee"><i data-feather="edit"></i></a>
                                                @endcan
                                                @can('events.delete')
                                                    <button class="btn btn-danger rem-element px-2 py-1" title="Delete Employee" data-id="{{ $event->id }}"><i data-feather="trash-2"></i> </button>
                                                @endcan

                                                <a href="{{ route('events.show',$event->id) }}" class="edit-element btn btn-secondary px-2 py-1" title="Show Event">
                                                    <i data-feather="eye"></i>
                                                </a>

                                            </td>

                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




</x-admin.layout>

<!-- Delete -->
<script>
    $("#buttons-datatables").on("click", ".rem-element", function(e) {
        e.preventDefault();
        swal({
            title: "Are you sure to delete this event?",
            // text: "Make sure if you have filled Vendor details before proceeding further",
            icon: "info",
            buttons: ["Cancel", "Confirm"]
        })
        .then((justTransfer) =>
        {
            if (justTransfer)
            {
                var model_id = $(this).attr("data-id");
                var url = "{{ route('events.destroy', ":model_id") }}";

                $.ajax({
                    url: url.replace(':model_id', model_id),
                    type: 'POST',
                    data: {
                        '_method': "DELETE",
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(data, textStatus, jqXHR) {
                        if (!data.error && !data.error2) {
                            swal("Success!", data.success, "success")
                                .then((action) => {
                                    window.location.reload();
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
            }
        });
    });
</script>
