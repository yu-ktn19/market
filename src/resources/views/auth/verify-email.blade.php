@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify">
    <p class="verify__text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a class="verify__button" href="http://localhost:8025" target="_blank">
        認証はこちらから
    </a>

    <form class="verify__form" method="GET" action="{{ route('verification.send') }}">
    @csrf
        <button class="verify__resend" type="submit">
            認証メールを再送する
        </button>
    </form>

    @if (session('message'))
        <p class="verify__message">{{ session('message') }}</p>
    @endif
</div>
@endsection