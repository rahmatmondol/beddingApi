@extends('admin.layouts.master')

@section('style')
    <style>
        .full-size-image {

            object-fit: cover; /* This will make sure the image covers the entire div without distorting */
            display: block; /* This makes the image a block-level element */
            margin: 0 auto;
            padding: 5%;
            border-radius: 40px;
            /* This will make sure the image covers the entire div without distorting */
        }
    </style>

@endsection
@section('title')
    <title>Service Details Page</title>
@endsection

@section('breadcrumb-title')
    <div class="breadcrumb-title pe-3">Service</div>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Service Details</li>
@endsection

@section('content')
    <div class="card">
        <div class="row g-0">
            <div class="col-md-4 ">
                <img src="{{$service->image}}" class="img-fluid full-size-image">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title">{{$service->name}}</h4>

{{--                    <div class="d-flex gap-3 py-3">--}}
{{--                        <div class="cursor-pointer">--}}
{{--                            @for($i = 1; $i <= 5; $i++)--}}
{{--                                @if($i <= $service->avg_rating)--}}
{{--                                    <i class='bx bxs-star text-warning'></i>--}}
{{--                                @else--}}
{{--                                    <i class='bx bxs-star text-secondary'></i>--}}
{{--                                @endif--}}
{{--                            @endfor--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div>{{ $service->skill }} Required skill</div>--}}
{{--                    <div>{{ $service->address }} Address</div>--}}
{{--                    <div>{{ $service->status }} Status</div>--}}
{{--                        <div class="text-success"><i class='bx bxs-cart-alt align-middle'></i> {{ $service->order_count }} orders</div>--}}
{{--                    </div>--}}
                    <div class="mb-3">
                        <span class="price h4">৳ {{ $service->price }}</span>
                        <span class="text-muted"> /{{$service->price_type}}</span>
                    </div>
{{--                    <p class="card-text fs-6">{{ $service->short_description}}</p>--}}
                    <p class="card-text fs-6">Service info</p>
                    <dl class="row">
                        <dt class="col-sm-3">Level</dt>
                        <dd class="col-sm-9">{{ $service->level }}</dd>

                        <dt class="col-sm-3">Skill Required</dt>

                        <dd class="col-sm-9">{{ $service->skill }}</dd>

                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9">{{$service->address}} </dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">{{$service->status}} </dd>
                    </dl>
                    <p class="card-text fs-6">Customer info</p>
                    <dl class="row">
{{--                        <img src="{{$service->customer->image}}" class="rounded-circle p-1 border" width="90" height="90" alt="...">--}}
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $service->customer->name }}</dd>

                        <dt class="col-sm-3">Contract Number</dt>
                        <dd class="col-sm-9">{{ $service->customer->phone }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{$service->customer->email}} </dd>
                    </dl>
                </div>
            </div>
        <div class="card-body">
            <ul class="nav nav-tabs nav-primary mb-0" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-comment-detail font-18 me-1'></i>
                            </div>
                            <div class="tab-title"> Product Description </div>
                        </div>
                    </a>
                </li>
{{--                <li class="nav-item" role="presentation">--}}
{{--                    <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <div class="tab-icon"><i class='bx bx-bookmark-alt font-18 me-1'></i>--}}
{{--                            </div>--}}
{{--                            <div class="tab-title">Extra Service</div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#primarycontact" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-star font-18 me-1'></i>
                            </div>
                            <div class="tab-title">Betting List</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content pt-3">
                <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                    {!! $service->description !!}
                </div>
{{--                <div class="tab-pane fade" id="primaryprofile" role="tabpanel">--}}
{{--                    @foreach($extra_services as $item)--}}
{{--                        <ul class="list-unstyled">--}}
{{--                            <li class="d-flex align-items-center border-bottom pb-2">--}}
{{--                                <div class="flex-grow-1 ms-3">--}}
{{--                                    <h5 class="mt-0 mb-1">{{ $item->name }} </h5>--}}
{{--                                    <span class="price h6 text-success">৳{{ $item->min_price }}</span>--}}

{{--                            </li>--}}

{{--                        </ul>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
                <div class="tab-pane fade" id="primarycontact" role="tabpanel">
                    <ul class="list-unstyled">
                        @foreach($reviews as $item)
                            <li class="d-flex align-items-center border-bottom pb-2">
                                <img src="{{$item->provider->image}}" class="rounded-circle p-1 border" width="90" height="90" alt="...">
                                <div class="flex-grow-1 ms-3">
                                    <h5  class="mt-0 mb-1"><a href="{{route('add.category')}}" class="caret-black">{{ $item->provider->name }}</a></h5>
                                    {{$item->additional_details}}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        </div>



    </div>
@endsection