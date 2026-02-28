<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — EduERP</title>
  <script>(function(){const t=localStorage.getItem('theme'),d=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||((!t)&&d))document.documentElement.classList.add('dark')})();</script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body style="background:var(--bg-base);font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;">

<div class="flex min-h-screen w-full">

  {{-- Left Panel --}}
  <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden"
       style="background:#16120E;">
    {{-- Glow effects --}}
    <div style="position:absolute;top:-100px;right:-100px;width:350px;height:350px;background:radial-gradient(circle,rgba(212,80,30,0.2) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:-80px;width:280px;height:280px;background:radial-gradient(circle,rgba(26,82,118,0.15) 0%,transparent 70%);pointer-events:none;"></div>

    {{-- Logo --}}
    <div class="flex items-center gap-3 relative z-10">
      <div style="width:40px;height:40px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;">
        <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:18px;color:white;">E</span>
      </div>
      <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:18px;color:white;">EduERP</span>
    </div>

    {{-- Center content --}}
    <div class="relative z-10">
      <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:40px;color:white;line-height:1.15;letter-spacing:-1px;">
        Manage Smarter.<br>
        <span style="color:var(--accent);">Teach Better.</span>
      </h1>
      <p style="font-size:15px;color:rgba(255,255,255,0.45);margin-top:16px;line-height:1.6;max-width:340px;font-weight:300;">
        A complete ERP suite built for modern educational institutions — from admissions to financial reporting.
      </p>
      <div class="flex flex-col gap-3 mt-8">
        @foreach(['Student & Teacher Management', 'Fee Collection & Receipts', 'Financial Reports & Ledger'] as $feature)
        <div class="flex items-center gap-3">
          <div style="width:20px;height:20px;background:rgba(44,110,73,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="11" height="11" fill="none" stroke="var(--accent-green)" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <span style="font-size:13px;color:rgba(255,255,255,0.6);">{{ $feature }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Footer --}}
    <div style="font-size:11px;color:rgba(255,255,255,0.2);position:relative;z-index:10;">Trusted by 500+ educational institutions</div>
  </div>

  {{-- Right Panel — Form --}}
  <div class="flex-1 flex items-center justify-center p-6 lg:p-12" style="background:var(--bg-surface);">
    <div style="width:100%;max-width:400px;">

      <div class="mb-8">
        <div class="lg:hidden flex items-center gap-2 mb-6">
          <div style="width:32px;height:32px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:14px;color:white;">E</span>
          </div>
          <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">EduERP</span>
        </div>
        <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:26px;color:var(--text-primary);letter-spacing:-0.5px;">Welcome back</h2>
        <p style="font-size:14px;color:var(--text-muted);margin-top:4px;">Sign in to your account to continue</p>
      </div>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-4">
          <label style="font-size:12px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;">Email Address</label>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus
            placeholder="admin@school.edu"
            style="width:100%;height:50px;border:1.5px solid {{ $errors->has('email') ? 'var(--accent)' : 'var(--border-color)' }};border-radius:12px;padding:0 16px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;transition:border-color 0.2s,box-shadow 0.2s;font-family:'DM Sans',sans-serif;"
            onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px rgba(212,80,30,0.12)'"
            onblur="this.style.borderColor='var(--border-color)';this.style.boxShadow='none'">
          @error('email')<p style="font-size:11px;color:var(--accent);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
          <div class="flex items-center justify-between mb-1.5">
            <label style="font-size:12px;font-weight:500;color:var(--text-secondary);">Password</label>
            @if(Route::has('password.request'))
              <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--accent);font-weight:500;">Forgot password?</a>
            @endif
          </div>
          <div class="relative">
            <input type="password" id="password-field" name="password" required
              placeholder="••••••••"
              style="width:100%;height:50px;border:1.5px solid {{ $errors->has('password') ? 'var(--accent)' : 'var(--border-color)' }};border-radius:12px;padding:0 46px 0 16px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;transition:border-color 0.2s,box-shadow 0.2s;font-family:'DM Sans',sans-serif;"
              onfocus="this.style.borderColor='var(--accent)';this.style.boxShadow='0 0 0 3px rgba(212,80,30,0.12)'"
              onblur="this.style.borderColor='var(--border-color)';this.style.boxShadow='none'">
            <button type="button" onclick="const f=document.getElementById('password-field');f.type=f.type==='password'?'text':'password'"
              style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);background:none;border:none;cursor:pointer;">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('password')<p style="font-size:11px;color:var(--accent);margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2 mb-6">
          <input type="checkbox" name="remember" id="remember"
            style="width:16px;height:16px;accent-color:var(--accent);border-radius:4px;cursor:pointer;">
          <label for="remember" style="font-size:13px;color:var(--text-secondary);cursor:pointer;">Remember me</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl py-3 px-4 font-bold text-[15px] transition-colors mb-3">
          Sign In →
        </button>
      </form>

      <p style="text-align:center;font-size:11px;color:var(--text-muted);margin-top:24px;">
        © {{ date('Y') }} EduERP · Educational Institution Suite
      </p>
    </div>
  </div>
</div>

</body>
</html>
