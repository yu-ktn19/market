@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')

<div class="address">
    <h2 class="address__title">住所の変更</h2>

    <form class="address-form" action="/purchase/address/{{ $item->id }}" method="post">
    @csrf
        <input type="hidden" name="payment_method" value="{{ $payment_method }}">

        <div class="address-form__group">
            <label class="address-form__label">郵便番号</label>
            <input class="address-form__input" type="text" name="postal_code" value="{{ $user->postal_code }}">
        </div>

        <div class="address-form__group">
            <label class="address-form__label">住所</label>
            <input class="address-form__input" type="text" name="address" value="{{ $user->address }}">
        </div>

        <div class="address-form__group">
            <label class="address-form__label">建物名</label>
            <input class="address-form__input" type="text" name="building" value="{{ $user->building }}">
        </div>
        
        <div class="address-form__button">
            <button class="address-form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection