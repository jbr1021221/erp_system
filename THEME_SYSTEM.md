# Dark/Light Theme System - Implementation Guide

## 🎨 Overview

This ERP system now features a **premium dark/light theme toggle** with smooth transitions, CSS custom properties, and localStorage persistence.

## ✨ Features

### 1. **Automatic Theme Detection**
- Detects system preference (dark/light mode)
- Remembers user's choice via localStorage
- Syncs with system theme changes

### 2. **Smooth Transitions**
- All color changes animate smoothly (200ms)
- Premium fade-in animations for elements
- Glassmorphic effects that adapt to theme

### 3. **CSS Custom Properties**
- Centralized theming via CSS variables
- Easy to customize colors
- Consistent across all components

### 4. **Premium UI Components**
- Modern glassmorphic cards
- Gradient borders
- Glow effects
- Smooth hover states
- Responsive design

## 🚀 Usage

### Theme Toggle Button
Located in the top-right header, next to the user profile dropdown:
- **Moon icon** = Light mode (click to switch to dark)
- **Sun icon** = Dark mode (click to switch to light)

### JavaScript API
```javascript
// Toggle theme
toggleTheme();

// Get current theme
const theme = getCurrentTheme(); // Returns 'light' or 'dark'
```

## 🎨 Color System

### Light Mode Colors
- **Background Primary**: slate-50 (#f8fafc)
- **Background Secondary**: slate-100 (#f1f5f9)
- **Text Primary**: slate-900 (#0f172a)
- **Accent**: indigo-500 (#6366f1)

### Dark Mode Colors
- **Background Primary**: slate-900 (#0f172a)
- **Background Secondary**: slate-800 (#1e293b)
- **Text Primary**: slate-50 (#f8fafc)
- **Accent**: indigo-400 (#818cf8)

## 🛠️ CSS Variables Reference

### Background Colors
```css
--bg-primary        /* Main background */
--bg-secondary      /* Secondary background */
--bg-tertiary       /* Tertiary background */
--bg-elevated       /* Cards, modals */
--bg-sidebar        /* Sidebar background */
--bg-sidebar-hover  /* Sidebar hover state */
```

### Text Colors
```css
--text-primary      /* Primary text */
--text-secondary    /* Secondary text */
--text-tertiary     /* Tertiary/muted text */
--text-inverse      /* Inverse text (white on dark, dark on light) */
```

### Border Colors
```css
--border-primary    /* Primary borders */
--border-secondary  /* Secondary borders */
--border-tertiary   /* Tertiary borders */
```

### Accent Colors
```css
--accent-primary    /* Primary accent color */
--accent-hover      /* Accent hover state */
--accent-light      /* Light accent background */
```

### Status Colors
```css
--success           /* Success color */
--success-light     /* Success background */
--warning           /* Warning color */
--warning-light     /* Warning background */
--error             /* Error color */
--error-light       /* Error background */
```

## 📦 Premium Components

### 1. **Glass Card**
```html
<div class="bg-glass p-6 rounded-xl">
    Content here
</div>
```

### 2. **Premium Card**
```html
<div class="card-premium p-6">
    Content here
</div>
```

### 3. **Primary Button**
```html
<button class="btn-primary">
    Click Me
</button>
```

### 4. **Premium Input**
```html
<input type="text" class="input-premium" placeholder="Enter text...">
```

### 5. **Badges**
```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-error">Error</span>
```

### 6. **Gradient Border**
```html
<div class="gradient-border p-6">
    Content with gradient border
</div>
```

### 7. **Glow Effect**
```html
<div class="glow-effect">
    Content with glow
</div>
```

## 🎭 Animations

### Fade In
```html
<div class="animate-fade-in">
    Fades in on load
</div>
```

### Slide In
```html
<div class="animate-slide-in">
    Slides in from left
</div>
```

### Pulse
```html
<div class="animate-pulse">
    Pulses continuously
</div>
```

## 🔧 Customization

### Changing Theme Colors

Edit `/resources/css/app.css` and modify the CSS variables:

```css
:root {
    /* Light mode */
    --accent-primary: 99 102 241;  /* Change to your color */
}

[data-theme="dark"] {
    /* Dark mode */
    --accent-primary: 129 140 248; /* Change to your color */
}
```

### Adding New Components

Use the existing CSS variables for consistency:

```css
.my-component {
    background: rgb(var(--bg-elevated));
    color: rgb(var(--text-primary));
    border: 1px solid rgb(var(--border-primary));
}
```

## 📱 Responsive Design

All components are fully responsive:
- **Desktop**: Full features
- **Tablet**: Optimized layouts
- **Mobile**: Touch-friendly, simplified UI

## ⚡ Performance

- **Smooth transitions**: 200ms cubic-bezier easing
- **Optimized animations**: GPU-accelerated
- **Minimal repaints**: CSS variables prevent layout shifts
- **LocalStorage**: Instant theme recall

## 🎯 Best Practices

1. **Always use CSS variables** for colors
2. **Avoid hardcoded colors** in Blade templates
3. **Use inline styles** with CSS variables for dynamic theming
4. **Test both themes** when adding new components
5. **Use semantic color names** (e.g., `--text-primary` not `--gray-900`)

## 🐛 Troubleshooting

### Theme not persisting
- Check browser localStorage is enabled
- Clear cache and reload

### Colors not changing
- Ensure you're using CSS variables
- Check for hardcoded Tailwind classes

### Transitions too slow/fast
- Adjust transition duration in `app.css`

## 📚 File Structure

```
resources/
├── css/
│   └── app.css          # Theme variables & components
├── js/
│   └── app.js           # Theme toggle logic
└── views/
    └── layouts/
        └── app.blade.php # Main layout with theme toggle
```

## 🎉 Next Steps

1. **Customize colors** to match your brand
2. **Add more components** using the CSS variables
3. **Create theme presets** (e.g., blue, green, purple)
4. **Add theme selector** for multiple color schemes

---

**Created**: February 7, 2026
**Version**: 1.0
**Author**: Antigravity AI
