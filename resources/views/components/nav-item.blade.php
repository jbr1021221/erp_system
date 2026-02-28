@props(['route', 'icon', 'label', 'badge' => null])

@php
  $isActive = request()->routeIs($route) || request()->routeIs($route . '.*');
  $icons = [
    'grid'    => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
    'users'   => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
    'user'    => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'book'    => '<path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>',
    'dollar'  => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>',
    'list'    => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
    'receipt' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>',
    'person'  => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    'cog'     => '<circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>',
  ];
  $svgPath = $icons[$icon] ?? $icons['list'];
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm transition-all duration-200 group"
   style="{{ $isActive
     ? 'background:var(--accent);color:white;font-weight:500;'
     : 'color:rgba(255,255,255,0.5);' }}"
   onmouseover="{{ !$isActive ? "this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.85)'" : '' }}"
   onmouseout="{{ !$isActive ? "this.style.background='transparent';this.style.color='rgba(255,255,255,0.5)'" : '' }}"
>
  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
       style="opacity:{{ $isActive ? '1' : '0.6' }};flex-shrink:0;">
    {!! $svgPath !!}
  </svg>
  <span style="flex:1;">{{ $label }}</span>
  @if($badge)
    <span style="background:var(--accent-gold);color:white;font-size:10px;font-weight:700;padding:2px 7px;border-radius:99px;">{{ $badge }}</span>
  @endif
</a>
