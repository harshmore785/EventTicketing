<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <form href="{{ route('user-dashboard.index') }}" class="theme-form" name="addForm" id="addForm" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="mb-3 row">

                            <div class="col-md-3">
                                <label class="col-form-label" for="location">Location<span class="text-danger"></span></label>
                                <input class="form-control title" id="location" name="location" type="text" placeholder="Location" value="{{ $location }}">
                                <span class="text-danger invalid location_err"></span>
                            </div>

                            <div class="col-md-3">
                                <label class="col-form-label" for="date">Date<span class="text-danger"></span></label>
                                <input class="form-control title" id="date" name="date" type="date" placeholder="Date" value="{{ $date }}">
                                <span class="text-danger invalid date_err"></span>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="addSubmit">Show</button>
                        <a href="{{ route('user-dashboard.index') }}" class="btn btn-warning">Refresh</a>
                    </div>
                </form>

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
                                @foreach ($event_details as $event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->date }}</td>
                                    <td>{{ $event->location }}</td>
                                    <td>{{ $event->description }}</td>
                                    <td>{{ ($event->status == 1) ? 'Active':''  }}</td>
                                    <td>
                                        @can('events.show')
                                        <a href="{{ route('events.show',$event->id) }}" class="edit-element btn btn-secondary px-2 py-1" title="Show Employee">
                                                    <i data-feather="eye"></i>
                                        </a>
                                        @endcan
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush

</x-admin.layout>
