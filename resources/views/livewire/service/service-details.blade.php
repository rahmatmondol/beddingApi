<div>
    <div class="tf-section-2 product-detail">
        <div class="themesflat-container">
            <pre>
        {{ $service }}
       </pre>
            <div class="row">

                <div class="col-md-6">
                    <div data-wow-delay="0s" class="wow fadeInLeft tf-card-box style-5">
                        <div class="card-media mb-0">
                            <a href="#">
                                <img src="{{ asset('user') }}/assets/images/box-item/product-01.jpg" alt="">
                            </a>
                        </div>
                        <h6 class="price gem"><i class="icon-gem"></i></h6>
                        <div class="wishlist-button">10<i class="icon-heart"></i></div>
                        <div class="featured-countdown">
                            <span class="js-countdown" data-timer="7500" data-labels="d,h,m,s"></span>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div data-wow-delay="0s" class="wow fadeInRight infor-product">
                        <div class="text">8SIAN Main Collection <span class="icon-tick"><span class="path1"></span><span
                                    class="path2"></span></span></div>
                        <div class="menu_card">
                            <div class="dropdown">
                                <div class="icon">
                                    <a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="icon-link-1"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#"><i class="icon-link"></i>Copy link</a>
                                        <a class="dropdown-item" href="#"><i class="icon-facebook"></i>Share on
                                            facebook</a>
                                        <a class="dropdown-item mb-0" href="#"><i class="icon-twitter"></i>Share
                                            on twitter</a>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <div class="icon">
                                    <a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="icon-content"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#"><i class="icon-refresh"></i>Refresh
                                            metadata</a>
                                        <a class="dropdown-item mb-0" href="#"><i class="icon-report"></i>Report</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>{{ $service->name }}</h2>

                        <div class="author flex items-center mb-30">
                            <div class="avatar">
                                <img src="{{ asset('user') }}/assets/images/avatar/avatar-box-05.jpg" alt="Image">
                            </div>
                            <div class="info">
                                <span>Owned by:</span>
                                <h6><a href="author-1.html">Marvin McKinney</a> </h6>
                            </div>
                        </div>
                        <div class="meta mb-20">
                            <div class="meta-item view">
                                <i class="icon-show"></i>208 view
                            </div>
                            <div class="meta-item rating">
                                <i class="icon-link-2"></i>Top #2 trending
                            </div>
                            <div class="meta-item favorites">
                                <i class="icon-heart"></i>10 favorites
                            </div>
                        </div>
                    </div>
                    <div data-wow-delay="0s" class="wow fadeInRight product-item time-sales">
                        <h6><i class="icon-clock"></i>Sale ends May 22 at 9:39</h6>
                        <div class="content">
                            <div class="text">Current price</div>
                            <div class="flex justify-between">
                                <p>{{ $service->price }}{{ $service->currency == "usd" ? "$" : $service->currency }}</p>
                                <a href="#" data-toggle="modal" data-target="#popup_bid"
                                    class="tf-button style-1 h50 w216">Place a bid<i
                                        class="icon-arrow-up-right2"></i></a>
                            </div>
                        </div>
                    </div>
                    <div data-wow-delay="0s" class="wow fadeInRight product-item description">
                        <h6><i class="icon-description"></i>Description</h6>
                        <i class="icon-keyboard_arrow_down"></i>
                        <div class="content">
                            {!! $service->description !!}

                        </div>
                    </div>

                    <div data-wow-delay="0s" class="wow fadeInRight product-item details">
                        <h6><i class="icon-description"></i>Details</h6>
                        <i class="icon-keyboard_arrow_down"></i>
                        <div class="content">
                            <div class="details-item">
                                <span>Contract Address</span>
                                <span class="tf-color">{{ $service->location }}</span>
                            </div>
                            <div class="details-item">
                                <span>Token ID</span>
                                <span class="tf-color">0270</span>
                            </div>
                            <div class="details-item">
                                <span>Token Standard</span>
                                <span class="">ERC-721</span>
                            </div>
                            <div class="details-item">
                                <span>Chain</span>
                                <span class="">Ethereum</span>
                            </div>
                            <div class="details-item">
                                <span>Last Updated</span>
                                <span class="">8 months ago</span>
                            </div>
                            <div class="details-item mb-0">
                                <span>Creator Earnings</span>
                                <span class="">8%</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div data-wow-delay="0s" class="wow fadeInUp col-12">
                    <div class="product-item offers">
                        <h6><i class="icon-description"></i>Offers</h6>
                        <i class="icon-keyboard_arrow_down"></i>
                        <div class="content">
                            <div class="table-heading">
                                <div class="column">Price</div>
                                <div class="column">USD Price</div>
                                <div class="column">Quantity</div>
                                <div class="column">Floor Diference</div>
                                <div class="column">Expiration</div>
                                <div class="column">Form</div>
                            </div>
                            <div class="table-item">
                                <div class="column">
                                    <h6 class="price gem"><i class="icon-gem"></i>0,0034</h6>
                                </div>
                                <div class="column">$6,60</div>
                                <div class="column">3</div>
                                <div class="column">90% below</div>
                                <div class="column">In 26 day</div>
                                <div class="column"><span class="tf-color">273E40</span></div>
                            </div>
                            <div class="table-item">
                                <div class="column">
                                    <h6 class="price gem"><i class="icon-gem"></i>0,0034</h6>
                                </div>
                                <div class="column">$6,60</div>
                                <div class="column">3</div>
                                <div class="column">90% below</div>
                                <div class="column">In 26 day</div>
                                <div class="column"><span class="tf-color">273E40</span></div>
                            </div>
                            <div class="table-item">
                                <div class="column">
                                    <h6 class="price gem"><i class="icon-gem"></i>0,0034</h6>
                                </div>
                                <div class="column">$6,60</div>
                                <div class="column">3</div>
                                <div class="column">90% below</div>
                                <div class="column">In 26 day</div>
                                <div class="column"><span class="tf-color">273E40</span></div>
                            </div>
                            <div class="table-item">
                                <div class="column">
                                    <h6 class="price gem"><i class="icon-gem"></i>0,0034</h6>
                                </div>
                                <div class="column">$6,60</div>
                                <div class="column">3</div>
                                <div class="column">90% below</div>
                                <div class="column">In 26 day</div>
                                <div class="column"><span class="tf-color">273E40</span></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <livewire:releted-services-slider :categoryId="$service->category_id">

</div>