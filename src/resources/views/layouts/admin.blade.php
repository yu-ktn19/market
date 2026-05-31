<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}">
    <link rel="stylesheet" href="{{ asset('css/admin.css')}}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__logo">
            <img src="{{ asset('storage/logo_images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
        </div>
        <form class="search-form" action="/" method="get">
        @csrf
            <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">
            <input type="text" name="keyword"  value="{{ request('keyword') }}" placeholder="なにをお探しですか？"  />
        </form>
        <nav class="header__nav">
            @guest
                <a href="/login">
                    <button type="button">ログイン</button>
                </a>
            @endguest
            @if (Auth::check())
            <div>
                <form class="form" action="/logout" method="post">
                @csrf
                    <button type="submit">ログアウト</button>
                </form>
            </div>
            @endif
            <div>
                <a href="/mypage">
                    <button type="submit">マイページ</button>
                </a>
            </div>
            <div>
                <a href="/sell" class="sell-btn">
                    <button type="submit">出品</button>
                </a>
            </div>
         </nav>
    </header>
    @yield('content')
</body>
</html>