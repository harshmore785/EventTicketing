<x-admin.layout>
    <x-slot name="title">Questions History</x-slot>
    <x-slot name="heading">Questions History</x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Event Description</th>
                                    <th>subject</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $question)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $question?->event?->title }}</td>
                                    <td>{{ $question?->event?->date }}</td>
                                    <td>{{ $question?->event?->location }}</td>
                                    <td>{{ $question?->event?->description }}</td>
                                    <td>{{ $question?->subject }}</td>
                                    <td>{{ $question?->description }}</td>


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
