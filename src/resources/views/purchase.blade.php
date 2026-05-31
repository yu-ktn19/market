@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__main">
        <div class="purchase__item">
            <div class="purchase__item-image">
                <img src="{{ asset($item->image_path) }}" width="300" height="300">
            </div>
            <div class="purchase__item-info">
                <p class="purchase__item-name">{{ $item->name }}</p>
                <p class="purchase__item-price">&yen;<span class="price">{{ number_format($item->price) }}</span></p>
            </div>
        </div>

        <form class="purchase__payment-form" action="/purchase/{{ $item->id }}" method="get">
        @csrf
            <input type="hidden" name="postal_code" value="{{ $postal_code }}">
            <input type="hidden" name="address" value="{{ $address }}">
            <input type="hidden" name="building" value="{{ $building }}">

            <label class="purchase__label">支払い方法</label>
            <select class="purchase__select" name="payment_method" onchange="this.form.submit()">
                <option value="" disabled hidden
                {{ empty($payment_method) ? 'selected' : '' }}>選択してください</option>
                <option value="convenience" {{ $payment_method === 'convenience' ? 'selected' : '' }}>
                コンビニ支払い
                </option>
                <option value="card" {{ $payment_method === 'card' ? 'selected' : '' }}>
                カード支払い
                </option>
            </select>
        </form>
        <div class="purchase__address">
            <p class="purchase__address-title">配送先</p>
            <div class="purchase__address-text">
                <p>〒{{ $postal_code }}</p>
                <p>{{ $address }}</p>
                <p>{{ $building }}</p>
            </div>

            <a class="purchase__address-link" href="/purchase/address/{{ $item->id }}?payment_method={{ $payment_method }}">変更する</a>
        </div>
    </div>
    <div class="purchase__side">
        <table class="purchase-table">
            <tr>
                <th>商品代金</th>
                <td>
                    <span class="price-mark">&yen;</span>
                    <span class="price-number">{{ number_format($item->price) }}</span>
                </td>
            </tr>
            <tr>
                <th>支払い方法</th>
                <td>
                    @if ($payment_method === 'convenience')
                        コンビニ支払い
                    @elseif ($payment_method === 'card')
                        カード支払い
                    @else
                        選択してください
                    @endif
                </td>
            </tr>
        </table>

        <form class="purchase__submit-form" action="/purchase/{{ $item->id }}" method="post">
        @csrf

            <input type="hidden" name="postal_code" value="{{ $postal_code }}">
            <input type="hidden" name="address" value="{{ $address }}">
            <input type="hidden" name="building" value="{{ $building }}">
            <input type="hidden" name="payment_method" value="{{ $payment_method }}">

            <button class="purchase__button" type="submit">購入する</button>
        </form>
    </div>  
</div>
@endsection