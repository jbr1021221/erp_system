<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', config('app.name')) — EduERP</title>

  {{-- Prevent flash of wrong theme --}}
  <script>
    (function(){
      const t = localStorage.getItem('theme');
      const d = window.matchMedia('(prefers-color-scheme: dark)').matches;
      if (t === 'dark' || (!t && d)) document.documentElement.classList.add('dark');
    })();
  </script>

  {{-- Fonts: Outfit (display) + Plus Jakarta Sans (ui) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  @stack('styles')
</head>
<body class="antialiased"
  style="background:var(--bg-base);color:var(--text-primary);"
  x-data="{ sidebarOpen: false }"
  @keydown.escape="sidebarOpen = false">

  {{-- Flash data for toast --}}
  @if(session('success'))
    <div id="flash-data" data-type="success" data-message="{{ session('success') }}" class="hidden"></div>
  @elseif(session('error'))
    <div id="flash-data" data-type="error" data-message="{{ session('error') }}" class="hidden"></div>
  @elseif(session('warning'))
    <div id="flash-data" data-type="warning" data-message="{{ session('warning') }}" class="hidden"></div>
  @elseif(session('info'))
    <div id="flash-data" data-type="info" data-message="{{ session('info') }}" class="hidden"></div>
  @endif

  {{-- Mobile overlay --}}
  <div x-show="sidebarOpen" x-transition.opacity
    class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    @click="sidebarOpen = false">
  </div>

  <div class="flex min-h-screen">
    @include('layouts.sidebar')

    <div class="flex flex-col flex-1 min-w-0 lg:ml-[260px]">
      @include('layouts.header')

      <main class="flex-1 p-6 lg:p-8">
        @yield('content')
      </main>

      <footer class="py-4 px-8 border-t text-center" style="border-color:var(--border-color);">
        <span style="font-size:11px;color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif;">
          © {{ date('Y') }} EduERP · Educational Institution Suite · v2.0
        </span>
      </footer>
    </div>
  </div>

  {{-- Toast container --}}
  <div id="toast-container"
    class="fixed bottom-6 right-6 z-50 flex flex-col gap-3"
    style="pointer-events:none; z-index:9999;">
  </div>

  <script src="{{ asset('js/app-ui.js') }}"></script>
  @stack('scripts')
</body>
</html>