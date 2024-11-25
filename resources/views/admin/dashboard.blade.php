@php
    use Illuminate\Support\Facades\Auth;
    $authUser = Auth::user();
@endphp
<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

     <div class="row">
        <div class="col-xl-12">
            <div class="d-flex flex-column h-100">
                <div class="row">
                    <div class="col-xl-4 col-md-4">
                        <div class="card card-animate overflow-hidden">
                            <div class="card-body bg-primary-subtle" style="z-index:1 ;">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-semibold text-muted text-truncate mb-3"> Total Events</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="{{ $totalEvents }}">{{ $totalEvents }}</span></h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div id="total_jobs" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!--end col-->
                    <div class="col-xl-4 col-md-4">
                        <!-- card -->
                        <div class="card card-animate overflow-hidden">
                            <div class="card-body bg-secondary-subtle" style="z-index:1 ;">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-semibold text-muted text-truncate mb-3"> Today's Event</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="{{ $todaysEvents }}">{{ $todaysEvents }}</span></h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div id="apply_jobs" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-xl-4 col-md-4">
                        <!-- card -->
                        <div class="card card-animate overflow-hidden">
                            <div class="card-body bg-success-subtle" style="z-index:1 ;">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-semibold text-muted text-truncate mb-3">Cancel Event</p>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="{{ $cancelEvents }}">{{ $cancelEvents }}</span></h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div id="new_jobs_chart" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->        
                </div><!--end row-->
            </div>
        </div><!--end col-->
   
    </div><!--end row-->


    @push('scripts')
    @endpush

</x-admin.layout>




