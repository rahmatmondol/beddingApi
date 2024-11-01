<div class="flat-pages-title">
    <div class="widget-bg-line">
        <div class="wraper">
            <div class="bg-grid-line y bottom">
                <div class="bg-line"></div>
            </div>
        </div>
    </div>
   
    <div class="themesflat-container w1490">
        <div class="row">
            <div class="col-12 pages-title">
                <div class="relative">
                    <div class="swiper swiper-3d-7">
                        <div class="swiper-wrapper">
                            {{-- Loop through services --}}
                            @foreach ($services as $service)
                            <div class="swiper-slide">
                                <div class="tf-card-box">
                                    <div class="card-media">
                                        <a href="/service/{{ $service->id }}">
                                            <img src="{{ $service->image }}" alt="">
                                        </a>
                                        <span class="wishlist-button icon-heart"></span>
                                        <div class="featured-countdown">
                                            <span class="js-countdown" data-timer="7500" data-labels="d,h,m,s"></span>
                                        </div>
                                        <div class="button-place-bid">
                                            <a href="/service/{{ $service->id }}" class="tf-button"><span>Place Bid</span></a>
                                        </div>
                                    </div>
                                    <div class="meta-info text-center">
                                        <h5 class="name"><a href="nft-detail-2.html">{{ $service->name }}</a></h5>
                                        <h6 class="price gem"><i class="icon-gem"></i>{{ $service->price }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination pagination-number"></div>
                    </div>
                    <div class="swiper-button-next next-3d over"></div>
                    <div class="swiper-button-prev prev-3d over"></div>
                </div>
            </div>
        </div>
    </div>
</div>
