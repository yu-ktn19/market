@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/display.css') }}">
@endsection

@section('content')
<div class="sell">
    <h2 class="sell__title">商品の出品</h2>

    <form class="sell-form" action="/sell" method="post" enctype="multipart/form-data">
        @csrf

        <div class="sell-form__group">
            <label class="sell-form__label">商品画像</label>
            <div class="sell-form__image-box">
                <label class="sell-form__image-button">
                    画像を選択する
                    <input class="sell-form__image-input" type="file" name="image" hidden>
                </label>
            </div>
            <div class="form__error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <h3 class="sell-form__section-title">商品の詳細</h3>

        <div class="sell-form__group">
            <label class="sell-form__label">カテゴリー</label>
            <div class="sell-form__category-list">
                @foreach ($categories as $category)
                    <label class="sell-form__category-item">
                        <input type="checkbox" name="category_id" value="{{ $category->id }}">
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            <div class="form__error">
                @error('category_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="sell-form__group">
            <label class="sell-form__label">商品の状態</label>
            <select class="sell-form__select" name="condition">
                <option value="" disabled selected hidden>選択してください</option>
                <option value="良好">良好</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="状態が悪い">状態が悪い</option>
            </select>
            <div class="form__error">
                @error('condition')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <h3 class="sell-form__section-title">商品名と説明</h3>

        <div class="sell-form__group">
            <label class="sell-form__label">商品名</label>
            <input class="sell-form__input" type="text" name="name">
            <div class="form__error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="sell-form__group">
            <label class="sell-form__label">ブランド名</label>
            <input class="sell-form__input" type="text" name="brand_name">
        </div>

        <div class="sell-form__group">
            <label class="sell-form__label">商品の説明</label>
            <textarea class="sell-form__textarea" name="description"></textarea>
            <div class="form__error">
                @error('description')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="sell-form__group sell-form__price">
            <label class="sell-form__label">販売価格</label>
            <span class="sell-form__price-mark">
                ¥
            </span>
            <input class="sell-form__input" type="number" name="price">
            <div class="form__error">
                @error('price')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <button class="sell-form__button" type="submit">出品する</button>
    </form>
</div>
@endsection