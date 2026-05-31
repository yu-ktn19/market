@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail">
    <div class="detail__image">   
        <img src="{{ asset($item->image_path) }}" >
    </div>
    
    <div class="detail__content">
        <div class="detail__item">
            <h2 class="detail__item-name">{{ $item->name }}</h2>
            <p class="detail__brand">{{ $item->brand_name }}</p>
            <p class="detail__price">&yen;<span class="price">{{ number_format($item->price) }}</span>(税込)</p>
        </div>

        <div class="detail__action">
            <div class="detail__like">
                @if(Auth::check())
                    @if($isLiked)
                    <form action="/item/{{ $item->id }}/like" method="post">
                    @csrf
                    @method('DELETE')
                        <button type="submit"><img src="{{ asset('storage/logo_images/ハートロゴ_ピンク.png') }}" alt=""></button>
                    </form>
                    @else
                        <form action="/item/{{ $item->id }}/like" method="post">
                        @csrf
                            <button type="submit"><img src="{{ asset('storage/logo_images/ハートロゴ_デフォルト.png') }}" alt=""></button>
                        </form>
                    @endif
                @else
                    <a href="/login"><img src="{{ asset('storage/logo_images/ハートロゴ_デフォルト.png') }}" alt=""></a>
                @endif

                <p>{{ $item->likes->count() }}</p>
            </div>

            <div class="detail__icon">
                <span><img src="{{ asset('storage/logo_images/ふきだしロゴ.png') }}" alt=""></span>
                <p>{{ $item->comments->count() }}</p>
            </div>
        </div>
        <div class="detail__purchase">
            @if(!$item->is_sold)
                @if(Auth::check())
                    <a class="detail__purchase-button" href="/purchase/{{ $item->id }}">購入手続きへ</a>
                @else
                    <a class="detail__purchase-button" href="/login">購入手続きへ</a>
                @endif
            @else
                <p class="detail__sold">Sold</p>
            @endif
        </div>
        <div class="detail__description">
            <h3 class="detail__title">商品説明</h3>
            <p class="detail__text">{{ $item->description }}</p>
        </div>

        <div class="detail__info">
            <h3 class="detail__title">商品の情報</h3>

            <div class="detail__category">
                <p class="detail__label">カテゴリー</p>
                <div class="detail__category-list">
                    @foreach ($item->categories as $category)
                        <span class="detail__category-tag">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="detail__condition">
                <p class="detail__label">商品の状態</p>
                <p>{{ $item->condition }}</p>
            </div>
        </div>

        <section class="detail__section">
            <h3 class="detail__comment-title">コメント({{ $item->comments->count() }})</h3>

            @foreach($item->comments as $comment)
                <div class="comment">
                    <div class="comment__user">
                        @if($comment->user->profile_image)
                            <<img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="">
                        @else
                            <div class="comment__user-image"></div>
                        @endif
                        <p>{{ $comment->user->name }}</p>
                    </div>

                    <p class="comment__text">{{ $comment->content }}</p>
                </div>
            @endforeach
        </section>

        <form class="comment-form"
            action="{{ Auth::check() ? '/item/' . $item->id . '/comment' : '/login' }}"
            method="{{ Auth::check() ? 'post' : 'get' }}">
        @csrf
     
            <label class="comment-form__label" for="content">商品へのコメント</label>
            <textarea class="comment-form__textarea" name="content" id="content">{{ old('content') }}</textarea>

            @error('content')
                <p class="error">{{ $message }}</p>
            @enderror

            <button class="comment-form__button" type="submit">コメントを送信する</button>
        </form>
    </div>        
</div>
@endsection