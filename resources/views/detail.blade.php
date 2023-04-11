@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-6 m-2">
            @foreach ($tree[$id]['path'] as $item)
                @if (array_values($tree[$id]['path'])[0] != $item)
                    /
                @endif
                @if (count($tree[$item]['slug']) > 0)
                    <a href="/catalog/{{ implode('/', $tree[$item]['slug']) }}/">{{ $tree[$item]['key'] }}</a>
                @else
                    <a href="/catalog/">{{ $tree[$item]['key'] }}</a>
                @endif
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 d-flex flex-column">
            <div class="card">
                <ul>
                    @foreach ($tree as $key => $cel)
                        @if ($key == $id)
                            <li>
                                @for ($i = 1; $i <= count($cel['path']); $i++)
                                    -
                                @endfor
                                <b>{{ $cel['key'] }}</b>
                            </li>
                        @else
                            <li>
                                @for ($i = 1; $i <= count($cel['path']); $i++)
                                    -
                                @endfor
                                <a href="/catalog/{{ implode('/', $cel['slug']) }}"> {{ $cel['key'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-7">

            <div class="card">
                <div class="p-2 justify-content-start">
                    <div>
                        @if (!empty($el['prop']['photo']))
                            <style>
                                .swiper {
                                    width: 100%;
                                    height: 100%;
                                }

                                .swiper-slide {
                                    text-align: center;
                                    font-size: 18px;
                                    background: #fff;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                }

                                .swiper-slide img {
                                    display: block;
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                }

                                .zaeb{
                                    width: 400px;
                                }
                                .swiper {
                                    width: 100%;
                                    height: 300px;
                                    margin-left: auto;
                                    margin-right: auto;
                                }

                                .swiper-slide {
                                    background-size: cover;
                                    background-position: center;
                                }

                                .mySwiper2 {
                                    height: 80%;
                                    width: 100%;
                                    border: 1px solid black;
                                }

                                .mySwiper2 .swiper-slide {
                                    height: 300px;
                                    background-size: contain!important;
                                    background-repeat: no-repeat!important;
                                    background-position: center center!important;
                                }

                                .mySwiper {
                                    height: 20%;
                                    box-sizing: border-box;
                                    padding: 10px 0;
                                }

                                .mySwiper .swiper-slide {
                                    height: 50px;
                                    background-size: contain!important;
                                    background-repeat: no-repeat!important;
                                    background-position: center center!important;
                                    opacity: 0.4;
                                    border: 1px solid black;
                                }

                                .mySwiper .swiper-slide-thumb-active {
                                    opacity: 1;
                                }

                                .swiper-slide img {
                                    display: block;
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                }
                                .swiper-button-next{
                                    color:black;
                                }
                                .swiper-button-prev{
                                    color:black;
                                }
                            </style>
                            <div class="zaeb">
                                <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                                    class="swiper mySwiper2">
                                    <div class="swiper-wrapper">

                                        @foreach ($el['prop']['photo'] as $url)
                                            <div class="swiper-slide" style="background: url({{ $url }}); ">
                                            </div>
                                        @endforeach
                                        

                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">

                                        @foreach ($el['prop']['photo'] as $url)
                                        <div class="swiper-slide" style="background: url({{ $url }}); ">
                                        </div>
                                        @endforeach
                                        
                                    </div>
                                </div>
                            </div>
                            <script>
                                var swiper = new Swiper(".mySwiper", {
                                    loop: true,
                                    spaceBetween: 10,
                                    slidesPerView: 8,
                                    freeMode: true,
                                    watchSlidesProgress: true,
                                });
                                var swiper2 = new Swiper(".mySwiper2", {
                                    loop: true,
                                    spaceBetween: 10,
                                    navigation: {
                                        nextEl: ".swiper-button-next",
                                        prevEl: ".swiper-button-prev",
                                    },
                                    thumbs: {
                                        swiper: swiper,
                                    },
                                });
                            </script>
                        @else
                            <img src="{{ $el['prop']['DETAIL_PICTURE'] }}" style="width:300px;">
                        @endif
                    </div>
                    <div class="mx-5">
                        <h6>{{ $el['name'] }}</h6>
                        <ul>
                            @foreach ($el['prop'] as $key => $prop)
                                @if ($key == 'DETAIL_PICTURE' || $key == 'photo')
                                @else
                                    @if (is_array($prop))
                                        <li>{{ $key }}</li>
                                        <select>
                                            @foreach ($prop as $key => $prop)
                                                <option>{{ $prop }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <li>{{ $key }} - {{ $prop }}</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                        <button class="btn btn-primary">buy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('zhsmenu')
    <script>
        let zhs = new zhsmenu({!! $zhsmenu !!});
        zhs.init(".zhs");
    </script>
@endsection
@endsection
