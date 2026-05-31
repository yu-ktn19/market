@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile">
    <h2 class="profile__title">プロフィール設定</h2>
    <form action="/mypage/profile" method="post" enctype="multipart/form-data">
    @csrf
        <div class="profile__image-area">

            @if ($user->profile_image)
                <img
                class="profile__image"
                src="{{ asset('storage/' . $user->profile_image) }}"
                alt=""  
                >
            @else
                <div class="profile__image"></div>
            @endif

            <label class="profile__image-label">
                画像を選択する
                <input type="file" class="profile__image-input" name="profile_image" hidden>
            </label>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label">ユーザー名</label>
            <input type="text" class="profile-form__input" name="name"  value="{{ old('name', $user->name) }}" />
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label">郵便番号</label>
            <input type="number" class="profile-form__input"  name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"/>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label">住所</label>
            <input type="text" class="profile-form__input" name="address"  value="{{ old('address', $user->address) }}"/>
        </div>
        <div class="profile-form__group">
            <label class="profile-form__label">建物名</label>
            <input type="text" class="profile-form__input" name="building" value="{{ old('building', $user->building) }}"/>
        </div>
        <div class="profile-form__button">
            <button class="profile-form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection