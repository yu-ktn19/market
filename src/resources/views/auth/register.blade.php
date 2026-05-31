@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register1.css') }}">
@endsection

@section('content')
<div class="register">
    <h2 class="register__title">会員登録</h2>
    <form class="register-form" action="/register" method="post" novalidate>
    @csrf
        <div class="register-form__group">
            <label class="register-form__label">ユーザー名</label>
            <input type="text" class="register-form__input" name="name" placeholder="名前を入力" value="{{ old('name') }}" />
        
            <div class="form__error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="register-form__group">
            <label class="register-form__label">メールアドレス</label>
            <input type="email"  class="register-form__input" name="email" placeholder="メールアドレスを入力" value="{{ old('email') }}" />
            <div class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="register-form__group">
            <label class="register-form__label">パスワード</label>
            <input type="password" class="register-form__input" name="password" placeholder="パスワードを入力" />
        
            <div class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="register-form__group">
            <label class="register-form__label">確認用パスワード</label>
            <input type="password" class="register-form__input" name="password_confirmation" placeholder="パスワードを入力" />
        </div>
        <div class="register-form__button">
            <button class="register-form__button-submit" type="submit">登録する</button>
        </div>
        <div class="register-form__login">
            <a class="register-form__login-link" href="/login">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection