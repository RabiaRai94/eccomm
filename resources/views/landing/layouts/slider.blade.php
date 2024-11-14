<!-- Include jQuery (make sure it's included first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Slick CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

<!-- Include Slick JS (after jQuery) -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- Slick Slider Section -->
<section class="section-slide">
    <div class="wrap-slick1 rs1-slick1">
        <button class="arrow-slick1 prev-slick1 slick-arrow"><i class="fa-solid fa-chevron-left"></i></button>
        <div class="slick1">
            @foreach ($slides as $slide)
                <div class="item-slick1" style="background-image: url('{{ asset($slide['image']) }}');">
                    <div class="container h-full">
                        <div class="flex-col-l-m h-full p-t-100 p-b-30">
                            <div class="layer-slick1 animated" data-appear="{{ $slide['title_animation'] }}" data-delay="0">
                                <span class="ltext-202 cl2 respon2">
                                    {{ $slide['title'] }}
                                </span>
                            </div>

                            <div class="layer-slick1 animated" data-appear="{{ $slide['subtitle_animation'] }}" data-delay="800">
                                <h2 class="ltext-104 cl2 p-t-19 p-b-43 respon1">
                                    {{ $slide['subtitle'] }}
                                </h2>
                            </div>

                            <div class="layer-slick1 animated" data-appear="{{ $slide['button_animation'] }}" data-delay="1600">
                                <a href="{{ $slide['link'] }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                    {{ $slide['button_text'] }} <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="arrow-slick1 next-slick1 slick-arrow"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
</section>

<!-- Slick Slider Initialization -->
<script>
    $(document).ready(function(){
        $('.slick1').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: true,
            arrows: true,
            prevArrow: '.prev-slick1',
            nextArrow: '.next-slick1',
            fade: true,
            speed: 1000,
            cssEase: 'linear'
        });
    });
</script>

<!-- Slick CSS Styles -->
<style>
    .slick1 {
        width: 100%;
        position: relative;
    }

    .item-slick1 {
        height: 100vh;  /* Full viewport height */
        background-size: cover;
        background-position: center center;
    }

    .slick-arrow {
        position: absolute;
        top: 50%;
        z-index: 10;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        padding: 10px;
        color: #fff;
    }

    .prev-slick1 {
        left: 10px;
        transform: translateY(-50%);
    }

    .next-slick1 {
        right: 10px;
        transform: translateY(-50%);
    }
</style>
