@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="main">
    <div class="tab-menu">
        <a class="tab-menu__link {{ request('tab', 'recommend') === 'recommend' ? 'active' : '' }}"
          href="/?tab=recommend&keyword={{ request('keyword') }}">おすすめ</a>

        <a class="tab-menu__link {{ request('tab') === 'mylist' ? 'active' : '' }}"
         href="/?tab=mylist&keyword={{ request('keyword') }}">マイリスト</a>
    </div>
    <div class="product-list">
        @foreach ($items as $item)
            <a class="product-card" href="{{ url('/item/' . $item->id) }}"> 
                <div class="product-card__image">
                   <img src="{{ asset($item->image_path) }}" alt=""> 
                </div> 

                <p class="product-card__name">{{$item->name}}</p>
                                 
                <div class= "sold">
                    @if ($item->is_sold)
                        <span class="sold-label">Sold</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection