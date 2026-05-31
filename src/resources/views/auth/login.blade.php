@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login">
    <h2 class="login__title">ログイン</h2>

    <form action="/login" method="post" novalidate>
    @csrf
        <div class="login-form__group">
            <label class="login-form__label">メールアドレス</label>
            <input type="email" class="login-form__input" name="email" placeholder="メールアドレスを入力"  value="{{ old('email') }}" />
            <div class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="login-form__group">
            <label class="login-form__label">パスワード</label>
            <input type="password" class="login-form__input" name="password" placeholder="パスワードを入力" />
            <div class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="login-form__button">
            <button class="login-form__button-submit" type="submit">ログイン</button>
        </div>
        <div class="login-form__register">
            <a class="login-form__register-link" href="/register">アカウント作成はこちら</a>
        </div>
    </form>
</div>
@endsection