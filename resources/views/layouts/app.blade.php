<!doctype html>
<html @php(language_attributes())>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alterwnate icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
      <a class="sr-only focus:not-sr-only" href="#main">
        {{ __('Skip to content', 'sage') }}
      </a>

      @if (get_page_template_slug() !== 'template-landing.blade.php' && !is_404())
        @include('sections.header')
      @endif

      <main id="main" class="main">
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside class="sidebar">
          @yield('sidebar')
        </aside>
      @endif

      @if (!is_404())
        @include('sections.footer')
      @endif
    </div>

    @php(do_action('get_footer'))
    @php(wp_footer())
  </body>
</html>
