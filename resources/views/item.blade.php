@extends('layouts.main')
@section('content')

    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav class="breadcrumb bg-light mb-30">
            <a class="breadcrumb-item text-dark" href="#">Home</a>
            <a class="breadcrumb-item text-dark" href="#">Shop</a>
            <span class="breadcrumb-item active">Shop Detail</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
      <div class="row px-xl-5">
        <div class="col-lg-5 mb-30">
          <div
            id="product-carousel"
            class="carousel slide"
            data-ride="carousel"
          >
            <div class="carousel-inner bg-light">
              <div class="carousel-item active">
                <img class="w-100 h-100" src="{{ asset('storage/'. $product->image) }}" alt="Image" />
              </div>
            </div>
            <a
              class="carousel-control-prev"
              href="#product-carousel"
              data-slide="prev"
            >
              <i class="fa fa-2x fa-angle-left text-dark"></i>
            </a>
            <a
              class="carousel-control-next"
              href="#product-carousel"
              data-slide="next"
            >
              <i class="fa fa-2x fa-angle-right text-dark"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-7 h-auto mb-30">
          <div class="h-100 bg-light p-30">
            <h3>{{ $product->name }}</h3>
            <div class="d-flex mb-3">
              @include('stars', ['rating' => $product->rating])
              <small class="pt-1">({{ $product->rating_count }} Reviews)</small>
            </div>
            <h3 class="font-weight-semi-bold mb-4">${{ number_format($product->price, 2, '.') }}</h3>
            <p class="mb-4">
              {{ $product->description }}
            </p>
            
            
            <div class="d-flex align-items-center mb-4 pt-2">
              <div class="input-group quantity mr-3" style="width: 130px">
                <div class="input-group-btn">
                  <button class="btn btn-primary btn-minus">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
                <input
                  type="text"
                  class="form-control bg-secondary border-0 text-center"
                  value="1"
                  id="quantity"
                />
                <div class="input-group-btn">
                  <button class="btn btn-primary btn-plus">
                    <i class="fa fa-plus"></i>
                  </button>
                </div>
              </div>
              <a class="btn btn-primary px-3" onclick="addProductToSession({{ $product['id'] }})">
                <i class="fa fa-shopping-cart mr-1"></i> Add To Cart
              </a>
            </div>
            <div class="d-flex pt-2">
              <strong class="text-dark mr-2">Share on:</strong>
              <div class="d-inline-flex">
                <a class="text-dark px-2" href="">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a class="text-dark px-2" href="">
                  <i class="fab fa-twitter"></i>
                </a>
                <a class="text-dark px-2" href="">
                  <i class="fab fa-linkedin-in"></i>
                </a>
                <a class="text-dark px-2" href="">
                  <i class="fab fa-pinterest"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row px-xl-5">
        <div class="col">
          <div class="bg-light p-30">
            <div class="nav nav-tabs mb-4">
              <a
                class="nav-item nav-link text-dark active"
                data-toggle="tab"
                href="#tab-pane-1"
                >Description</a
              >
              <a
                class="nav-item nav-link text-dark"
                data-toggle="tab"
                href="#tab-pane-3"
                >Reviews ({{ $product->rating_count }})</a
              >
            </div>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-pane-1">
                <h4 class="mb-3">Product Description</h4>
                <p>
                  {{ $product->description }}
                </p>
              </div>
              <div class="tab-pane fade" id="tab-pane-3">
                <div class="row">
                  <div class="col-md-6">
                    <h4 class="mb-4">{{ $product->rating_count }} review for "{{ $product->name }}"</h4>
                    @foreach ($reviews as $review)
                    <div class="media mb-4">
                      <div class="media-body">
                        <h6>
                          {{ $review->user->name }}<small> - <i>{{$review->created_at->format('d M Y')}}</i></small>
                        </h6>
                        @include('stars', ['rating' => $review->rating])
                        <p>
                          {{ $review->review }}
                        </p>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  <div class="col-md-6">
                    <h4 class="mb-4">Leave a review</h4>
                    <div class="d-flex my-3">
                      <p class="mb-0 mr-2">Your Rating * :</p>
                      <div class="text-primary">
                        <!-- <div class="form-rating">
                          <label class="rating-label"
                            ><i class="far fa-star-half"></i
                          ></label>
                          <label class="rating-label"
                            ><i class="far fa-star"></i
                          ></label>
                        </div> -->
                        <!-- <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i> -->
                      </div>
                    </div>
                    @auth
                    <form method="POST" action="{{ route('shop.review', $product->id) }}">
                      @csrf
                      <fieldset class="rate">
                        <input
                          type="radio"
                          id="rating10"
                          name="rating"
                          value="5"
                        /><label for="rating10" title="5 stars"></label>
                        <input
                          type="radio"
                          id="rating9"
                          name="rating"
                          value="4.5"
                        /><label
                          class="half"
                          for="rating9"
                          title="4 1/2 stars"
                        ></label>
                        <input
                          type="radio"
                          id="rating8"
                          name="rating"
                          value="4"
                        /><label for="rating8" title="4 stars"></label>
                        <input
                          type="radio"
                          id="rating7"
                          name="rating"
                          value="3.5"
                        /><label
                          class="half"
                          for="rating7"
                          title="3 1/2 stars"
                        ></label>
                        <input
                          type="radio"
                          id="rating6"
                          name="rating"
                          value="3"
                        /><label for="rating6" title="3 stars"></label>
                        <input
                          type="radio"
                          id="rating5"
                          name="rating"
                          value="2.5"
                        /><label
                          class="half"
                          for="rating5"
                          title="2 1/2 stars"
                        ></label>
                        <input
                          type="radio"
                          id="rating4"
                          name="rating"
                          value="2"
                        /><label for="rating4" title="2 stars"></label>
                        <input
                          type="radio"
                          id="rating3"
                          name="rating"
                          value="1.5"
                        /><label
                          class="half"
                          for="rating3"
                          title="1 1/2 stars"
                        ></label>
                        <input
                          type="radio"
                          id="rating2"
                          name="rating"
                          value="1"
                        /><label for="rating2" title="1 star"></label>
                        <input
                          type="radio"
                          id="rating1"
                          name="rating"
                          value="0.5"
                        /><label
                          class="half"
                          for="rating1"
                          title="1/2 star"
                        ></label>
                      </fieldset>
                      <div class="form-group">
                        <label for="message">Your Review *</label>
                        <textarea
                          id="message"
                          cols="30"
                          rows="5"
                          class="form-control" name="review"
                        ></textarea>
                      </div>
                      <div class="form-group mb-0">
                        <input
                          type="submit"
                          value="Leave Your Review"
                          class="btn btn-primary px-3"
                        />
                      </div>
                    </form>
                    @endauth
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Shop Detail End -->

    <!-- Products Start -->
    <div class="container-fluid py-5">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">You May Also Like</span>
      </h2>
      <div class="row px-xl-5">
        <div class="col">
          <div class="owl-carousel related-carousel">
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/product-1.jpg" alt="" />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/product-2.jpg" alt="" />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/product-3.jpg" alt="" />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/product-4.jpg" alt="" />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/product-5.jpg" alt="" />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Products End -->
@endsection
@section('scripts')
<script>
    function addProductToSession(id) {
        $.ajax({
            url: '{{ url('/add-product') }}',
            data: {
                id: id,
                quantity: $("#quantity").val()
            },
            success: (data) => {
              console.log(data);
                $('#product_count').html(data);
            }
        })
    }
</script>
@endsection