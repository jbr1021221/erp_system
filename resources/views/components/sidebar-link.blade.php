{{--
  resources/views/components/sidebar-link.blade.php
  Usage: <x-sidebar-link route="dashboard" label="Dashboard" routePattern="dashboard">
           <svg>...</svg>  ← icon slot
         </x-sidebar-link>
--}}
@props([
  'route',
  'label',
  'routePattern' => null,
])

@php
  $pattern = $routePattern ?? $route;
  $isActive = request()->routeIs($pattern);
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-[11px] mb-0.5 rounded-md text-sm font-medium transition-all duration-150 group relative"
   style="
     color: {{ $isActive ? 'var(--text-primary)' : 'var(--text-secondary)' }};
     background: {{ $isActive ? 'rgba(5, 150, 105, 0.08)' : 'transparent' }};
   "
   onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.background='var(--bg-surface-2)'; this.style.color='var(--text-primary)'; }"
   onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.background='transparent'; this.style.color='var(--text-secondary)'; }"
>
  {{-- Active indicator bar --}}
  @if($isActive)
  <span class="absolute left-0 top-0 bottom-0 w-[3px]" style="background:var(--accent); border-top-right-radius:3px; border-bottom-right-radius:3px;"></span>
  @endif

  {{-- Icon --}}
  <span class="flex-shrink-0" style="color: {{ $isActive ? 'var(--accent)' : 'var(--text-muted)' }};">
    {{ $slot }}
  </span>

  {{-- Label --}}
  <span>{{ $label }}</span>
</a>