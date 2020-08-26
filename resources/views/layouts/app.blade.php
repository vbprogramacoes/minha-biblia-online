<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @if( !app()->isLocal() )
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PMNX74H');</script>
    <!-- End Google Tag Manager -->
    <script data-ad-client="ca-pub-1349799617143625" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    @endif

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--  Meta  --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="0mtS77p4KIM-Y74fHPVFMTPNNCZG9y5MBWh9yeaeJR4" />
    <meta name="p:domain_verify" content="02fcbab6195bd21ccadfe022c40ef367"/>
    <link rel="canonical" href="{{  $page->canonical }}">

    {{-- Schema --}}
    @if(isset($page->schema))
        @foreach($page->schema as $item)
            {!! $item !!}
        @endforeach
    @endif

    {{--  Meta Title --}}
    <title>{{ $page->title }}</title>
    <meta name="title" content="{{ $page->meta_title }}">
    <meta name="description" content="{{ $page->meta_description }}">

    {{-- Social --}}
    <!-- Social -->
    <meta property="og:locale" content="pt_BR">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $page->meta_title }}">
    <meta property="og:description" content="{{ $page->meta_description }}">
    <meta property="og:url" content="{{ $page->canonical }}">
    <meta property="og:site_name" content="Nova Bíblia">
    <meta property="article:section" content="Nova Bíblia">
    <meta property="og:image" content="{{ $page->img }}">
    <meta property="og:image:secure_url" content="{{ $page->img }}">
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="424">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="{{ $page->meta_title }}">
    <meta name="twitter:image" content="{{ $page->img }}">

    <link rel="icon" href="/images/favicon.ico" />

    {{--  Scripts  --}}
    <script src="{{ mix('assets/js/script.js') }}" defer></script>

    {{--  Styles  --}}
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/icons.css">
</head>
<body>
    @if( !app()->isLocal() )
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PMNX74H"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    <nav class="navbar">
        <div class="container grid-lg text-center">
            <div class="columns">
                <div class="column col-3 col-sm-4 text-left">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="/images/logo.png" alt="Nova Bíblia" width="130" title="Nova Bíblia">
                    </a>
                </div>
                <form class="column col-7 mt-2 pt-1" action="/busca">
                    <div class="input-group input-inline">
                        <input class="form-input" type="text" placeholder="Buscar na bíblia" name="q" autocomplete="off" required>
                        <button class="btn btn-primary input-group-btn">
                        <svg  fill="#ffffff" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M23.111 20.058l-4.977-4.977c.965-1.52 1.523-3.322 1.523-5.251 0-5.42-4.409-9.83-9.829-9.83-5.42 0-9.828 4.41-9.828 9.83s4.408 9.83 9.829 9.83c1.834 0 3.552-.505 5.022-1.383l5.021 5.021c2.144 2.141 5.384-1.096 3.239-3.24zm-20.064-10.228c0-3.739 3.043-6.782 6.782-6.782s6.782 3.042 6.782 6.782-3.043 6.782-6.782 6.782-6.782-3.043-6.782-6.782zm2.01-1.764c1.984-4.599 8.664-4.066 9.922.749-2.534-2.974-6.993-3.294-9.922-.749z"/></svg>
                        </button>
                    </div>
                </form>
                <div class="column col-2 col-sm-1">
                    @include('layouts.components.nav')
                </div>
            </div>
        </div>
    </nav>

    @isset($page->breadcrumb)
    <div class="container grid-lg">
        <ul class="breadcrumb mt-0">
            @foreach($page->breadcrumb as $item => $path)
                <li class="breadcrumb-item">
                    <a href="{{ url($path) }}">{{ $item }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    @endisset()
    <div class="container">
        @include('layouts.ads.responsive')
    </div>
    <main class="container grid-lg">
        @yield('content')
    </main>
</body>
</html>
