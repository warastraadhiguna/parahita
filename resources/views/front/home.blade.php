@extends('front.layouts.app')

@section('content')
<section class="section-1">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">

            @foreach($sliders as $index => $slider)

            <div class="carousel-item {{ $index == 0? 'active' : '' }}">

                <picture>
                    {{-- <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/carousel-1.jpg') }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/carousel-1.jpg') }}" /> --}}
                    <img src="{{ asset('front-assets/images/' . $slider->image) }}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">{{ $slider->title }}</h1>
                        <p class="mx-md-5 px-5">{{ $slider->description }}</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Lihat Katalog</a>
                    </div>
                </div>
            </div>
                            
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<section class="section-2">
    <div class="container">
        <div class="row">
            @foreach ($benefits as $benefit)
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon {{ $benefit->icon }} text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">{{ $benefit->name }}</h5>
                </div>
            </div> 
            @endforeach
        </div>
    </div>
</section>
<section class="section-3">
    <div class="container">
        <div class="section-title">
            <h2>Kategori</h2>
        </div>
        <div class="row pb-3">
            @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $category)
            <div class="col-lg-3">
                <div class="cat-card">
                    <div class="left">
                        @if ($category->image != "")
                        <img src="{{ asset('uploads/category/thumb/'.$category->image) }}" alt="" class="img-fluid">
                        @endif
                        <!-- <img src="{{ asset('front-assets/images/cat-1.jpg') }}" alt="" class="img-fluid"> -->
                    </div>
                    <div class="right">
                        <div class="cat-data">
                            <h2>{{ $category->name }}</h2>
                            <!-- <p>100 Products</p> -->
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Produk Unggulan</h2>
        </div>
        <div class="row pb-3">
            @if ($featuredProducts->isNotEmpty())
            @foreach ($featuredProducts as $product)
            @php
                $productImage = $product->product_images->first();
            @endphp
            <div class="col-md-3">
                <div class="card product-card">
                    <div class="product-image position-relative">
                        <a href="{{ route('front.product',$product->slug) }}" class="product-img">

                            @if (!empty($productImage->image))
                            <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}" />
                            @else
                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                            @endif

                        </a>

                        <a onclick="addToWishlist({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>

                        <div class="product-action">
                            @if($product->track_qty == 'Yes')
                                 @if($product->qty > 0)
                                {{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a> --}}
                                <a class="btn btn-primary" href="javascript:void(0);">
                                    Stok Ada
                                </a>
                                @else 
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Stok Habis
                                </a>
                                @endif
                           
                            @else
                            {{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                <i class="fa fa-shopping-cart"></i> Add To Cart
                            </a> --}}
                                <a class="btn btn-primary" href="javascript:void(0);">
                                    Stok Ada
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="{{ route('front.product',$product->slug) }}">{{ $product->title }}</a>
                        <div class="price mt-2">

                            <span class="h5"><strong>{{ NumberFormat($product->price) }}</strong></span>
                            @if ($product->compare_price > 0)
                            <span class="h6 text-underline"><del>{{ NumberFormat($product->compare_price) }}</del></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

    {{-- <img src="https://msib-6-commerce-app-02.educalab.id/front-assets/images/home-promo1.png" style="height:450px;"> --}}
@if($company->youtube_id )
<div class="video-container mt-5"> 
    <iframe src="https://www.youtube.com/embed/{{ $company->youtube_id }}?autoplay=1&controls=0&modestbranding=1&rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen frameborder="0" ></iframe>
</div>
    
@endif  
<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Produk Terbaru</h2>
        </div>
        <div class="row pb-3">
            @if ($latestProducts->isNotEmpty())
            @foreach ($latestProducts as $product)
            @php
            $productImage = $product->product_images->first();
            @endphp
            <div class="col-md-3">
                <div class="card product-card">
                    <div class="product-image position-relative">
                        <a href="{{ route('front.product',$product->slug) }}" class="product-img">

                            @if (!empty($productImage->image))
                            <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}" />
                            @else
                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                            @endif

                        </a>

                        <a onclick="addToWishlist({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>

                        <div class="product-action">
                            @if($product->track_qty == 'Yes')
                                @if($product->qty > 0)
                                {{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a> --}}
                                <a class="btn btn-primary" href="javascript:void(0);">
                                    Stok Ada
                                </a>                                
                                @else 
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Stok Habis
                                </a>
                                @endif
                            @else
                            {{-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                <i class="fa fa-shopping-cart"></i> Add To Cart
                            </a> --}}
                                <a class="btn btn-primary" href="javascript:void(0);">
                                    Stok Ada
                                </a>                            
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="{{ route('front.product',$product->slug) }}">{{ $product->title }}</a>
                        <div class="price mt-2">

                            <span class="h5"><strong>{{ NumberFormat($product->price) }}</strong></span>
                            @if ($product->compare_price > 0)
                            <span class="h6 text-underline"><del>{{ NumberFormat($product->compare_price) }}</del></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>
@endsection