@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/page.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="mypage__profile">
        <div class="mypage__user">

            <img class="mypage__image" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('img/default.png') }}" alt=""> 
            

            <p class="mypage__name">{{ $user->name }}</p>
        </div>
            <a class="mypage__edit"  href="/mypage/profile">プロフィールを編集</a>
    </div>
    <div class="mypage__tab">
        <a class="mypage__tab-link {{ request('page', 'sell') === 'sell' ? 'active' : '' }}" href="/mypage?page=sell">出品した商品</a>
        <a class="mypage__tab-link {{ request('page') === 'buy' ? 'active' : '' }}" href="/mypage?page=buy">購入した商品</a>
    </div>
    
    <div class="product-list">
        @foreach ($items as $item)
            <a class="product-card" href="/item/{{ $item->id }}">
                <div class="product-card__image">
                    <img src="{{ asset($item->image_path) }}" alt="">
                </div>
                <p class="product-card__name">{{ $item->name }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection