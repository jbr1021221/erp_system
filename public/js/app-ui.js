// ─── DARK MODE ─────────────────────────────────────────────────────
function applyTheme(theme) {
  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  localStorage.setItem('theme', theme);
  // Notify charts/components to re-render with new theme
  document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
  updateChartTheme();
}

function toggleTheme() {
  const current = localStorage.getItem('theme') || 'light';
  applyTheme(current === 'dark' ? 'light' : 'dark');
}

// Apply on load (before DOM ready to avoid flash)
(function () {
  const saved = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  applyTheme(saved || (prefersDark ? 'dark' : 'light'));
})();


// ─── TOAST NOTIFICATIONS ───────────────────────────────────────────
const _toastIcons = {
  success: `<svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
  warning: `<svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>`,
  error:   `<svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
  info:    `<svg class="toast-icon" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>`,
};

window.toast = {
  show(type, title, message = '') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.style.pointerEvents = 'all';
    el.innerHTML = `
      ${_toastIcons[type] || _toastIcons.info}
      <div class="flex-1">
        <div class="toast-title">${title}</div>
        ${message ? `<div class="toast-msg">${message}</div>` : ''}
      </div>
      <button class="toast-close" onclick="this.closest('.toast').remove()" aria-label="Dismiss">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
      </button>`;
    container.appendChild(el);
    // Auto dismiss
    setTimeout(() => { el.style.transition = 'opacity 0.3s'; el.style.opacity = '0'; }, 3700);
    setTimeout(() => el.remove(), 4000);
  },
  success(title, msg) { this.show('success', title, msg); },
  warning(title, msg) { this.show('warning', title, msg); },
  error(title, msg)   { this.show('error', title, msg); },
  info(title, msg)    { this.show('info', title, msg); },
};

// Auto-show Laravel flash messages as toasts
document.addEventListener('DOMContentLoaded', () => {
  const flash = document.getElementById('flash-data');
  if (flash) {
    const type = flash.dataset.type || 'info';
    const msg  = flash.dataset.message;
    if (msg) window.toast[type](msg);
  }
});


// ─── COUNT-UP ANIMATION ────────────────────────────────────────────
function animateCount(el) {
  const target   = parseFloat(el.dataset.target || '0');
  const prefix   = el.dataset.prefix || '';
  const suffix   = el.dataset.suffix || '';
  const duration = 1200;
  const start    = performance.now();

  function step(now) {
    const progress = Math.min((now - start) / duration, 1);
    const ease     = 1 - Math.pow(1 - progress, 3); // cubic ease-out
    const value    = Math.round(ease * target);
    el.textContent = prefix + value.toLocaleString() + suffix;
    if (progress < 1) requestAnimationFrame(step);
    else el.textContent = prefix + target.toLocaleString() + suffix;
  }
  requestAnimationFrame(step);
}

const _countObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      animateCount(e.target);
      _countObserver.unobserve(e.target);
    }
  });
}, { threshold: 0.5 });

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-countup]').forEach(el => _countObserver.observe(el));
});


// ─── CHART.JS GLOBAL DEFAULTS — Emerald/Teal theme ─────────────────
function updateChartTheme() {
  if (typeof Chart === 'undefined') return;
  const isDark     = document.documentElement.classList.contains('dark');
  const gridColor  = isDark ? 'rgba(5,150,105,0.08)' : 'rgba(5,150,105,0.06)';
  const labelColor = isDark ? '#4D7A70' : '#7A9690';
  const bgColor    = isDark ? '#101F1A' : '#FAFCFB';
  const titleColor = isDark ? '#E8F5F0' : '#0D1F1A';
  const bodyColor  = isDark ? '#8BBFB2' : '#3D5A52';

  Chart.defaults.color        = labelColor;
  Chart.defaults.borderColor  = gridColor;
  Chart.defaults.font.family  = "'Plus Jakarta Sans', sans-serif";
  Chart.defaults.font.size    = 11;

  Chart.defaults.plugins.tooltip.backgroundColor = bgColor;
  Chart.defaults.plugins.tooltip.titleColor      = titleColor;
  Chart.defaults.plugins.tooltip.bodyColor       = bodyColor;
  Chart.defaults.plugins.tooltip.borderColor     = isDark ? 'rgba(5,150,105,0.15)' : 'rgba(5,150,105,0.1)';
  Chart.defaults.plugins.tooltip.borderWidth     = 1;
  Chart.defaults.plugins.tooltip.padding         = 12;
  Chart.defaults.plugins.tooltip.cornerRadius    = 10;
  Chart.defaults.plugins.tooltip.titleFont       = {
    family: "'Outfit', sans-serif", weight: '700', size: 12
  };
  Chart.defaults.plugins.tooltip.bodyFont = {
    family: "'Plus Jakarta Sans', sans-serif", size: 12
  };
}
document.addEventListener('DOMContentLoaded', updateChartTheme);


// ─── AVATAR HELPERS — Solid Flat palette ─────────────────────────
const _avatarSolidColors = [
  ['#059669', '#059669'],  // emerald
  ['#0d9488', '#0d9488'],  // teal
  ['#0284c7', '#0284c7'],  // sky
  ['#2563eb', '#2563eb'],  // blue
  ['#7c3aed', '#7c3aed'],  // violet
  ['#d97706', '#d97706'],  // amber
  ['#e11d48', '#e11d48'],  // rose
  ['#334155', '#334155'],  // slate
];

window.getAvatarGradient = function (name) {
  const idx = (name?.charCodeAt(0) || 65) % _avatarSolidColors.length;
  return _avatarSolidColors[idx];
};

window.getInitials = function (name) {
  if (!name) return '?';
  const parts = name.trim().split(' ');
  return (parts[0][0] + (parts[1] ? parts[1][0] : '')).toUpperCase();
};