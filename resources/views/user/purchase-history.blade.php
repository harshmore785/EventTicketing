<x-admin.layout>
    <x-slot name="title">Purchase History</x-slot>
    <x-slot name="heading">Purchase History</x-slot>

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
                                    <th>Description</th>
                                    <th>No. of Ticket</th>
                                    <th>Price</th>
                                    <th>Transaction Id</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $purchase?->event?->title }}</td>
                                    <td>{{ $purchase?->event?->date }}</td>
                                    <td>{{ $purchase?->event?->location }}</td>
                                    <td>{{ $purchase?->event?->description }}</td>
                                    <td>{{ $purchase?->quantity }}</td>
                                    <td>{{ $purchase?->total_price }}</td>
                                    <td>{{ $purchase?->transaction_id }}</td>


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
