# Font Size System - Dynamic Font Scaling

## Overview
Added a comprehensive font size management system to the GeneralSetting model that allows administrators to change the font size across the entire application dynamically.

## Features

✅ **4 Font Size Options**: Small, Medium (Default), Large, Extra Large
✅ **System-Wide Application**: Changes apply to all pages automatically
✅ **Caching**: Settings are cached for performance
✅ **CSS Variables**: Uses modern CSS custom properties
✅ **Responsive Scaling**: All text elements scale proportionally

## Font Size Options

### 1. Small (14px base)
- Base: 14px
- Small: 13px
- Large: 16px
- XL: 18px
- 2XL: 20px
- 3XL: 24px
- 4XL: 30px

### 2. Medium (16px base) - DEFAULT
- Base: 16px
- Small: 14px
- Large: 18px
- XL: 20px
- 2XL: 24px
- 3XL: 30px
- 4XL: 36px

### 3. Large (18px base)
- Base: 18px
- Small: 16px
- Large: 20px
- XL: 24px
- 2XL: 28px
- 3XL: 36px
- 4XL: 44px

### 4. Extra Large (20px base)
- Base: 20px
- Small: 18px
- Large: 24px
- XL: 28px
- 2XL: 32px
- 3XL: 40px
- 4XL: 48px

## Model Methods

### Get/Set Font Size

#### `getFontSize()`
Get the current font size setting.
```php
$currentSize = GeneralSetting::getFontSize();
// Returns: 'small', 'medium', 'large', or 'extra_large'
```

#### `setFontSize($size)`
Set the font size for the entire system.
```php
use App\Models\GeneralSetting;

// Set to large
GeneralSetting::setFontSize(GeneralSetting::FONT_SIZE_LARGE);

// Set to small
GeneralSetting::setFontSize('small');
```

#### `getFontSizeValues()`
Get all font size values for the current setting.
```php
$sizes = GeneralSetting::getFontSizeValues();
// Returns: ['base' => '1rem', 'sm' => '0.875rem', ...]
```

#### `getFontSizeOptions()`
Get all available font size options for a dropdown.
```php
$options = GeneralSetting::getFontSizeOptions();
// Returns: ['small' => 'Small', 'medium' => 'Medium (Default)', ...]
```

#### `getFontSizeCssVariables()`
Get CSS variables string for current font size.
```php
$css = GeneralSetting::getFontSizeCssVariables();
// Returns CSS variable declarations
```

### General Setting Methods

#### `getValue($key, $default)`
Get any setting value with caching.
```php
$value = GeneralSetting::getValue('font_size', 'medium');
```

#### `setValue($key, $value, $displayName, $group)`
Set any setting value.
```php
GeneralSetting::setValue('font_size', 'large', 'System Font Size', 'appearance');
```

#### `clearCache()`
Clear all settings cache.
```php
GeneralSetting::clearCache();
```

## Usage Examples

### Example 1: Create Font Size Settings Page

```php
// Controller
use App\Models\GeneralSetting;

class SettingsController extends Controller
{
    public function appearance()
    {
        $currentFontSize = GeneralSetting::getFontSize();
        $fontSizeOptions = GeneralSetting::getFontSizeOptions();
        
        return view('settings.appearance', compact('currentFontSize', 'fontSizeOptions'));
    }
    
    public function updateFontSize(Request $request)
    {
        $request->validate([
            'font_size' => 'required|in:small,medium,large,extra_large'
        ]);
        
        GeneralSetting::setFontSize($request->font_size);
        
        return redirect()->back()->with('success', 'Font size updated successfully!');
    }
}
```

```blade
{{-- View: settings/appearance.blade.php --}}
<form method="POST" action="{{ route('settings.update-font-size') }}">
    @csrf
    <div class="mb-4">
        <label class="block text-sm font-medium mb-2">System Font Size</label>
        <select name="font_size" class="form-select">
            @foreach($fontSizeOptions as $value => $label)
                <option value="{{ $value }}" {{ $currentFontSize === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
```

### Example 2: Programmatically Change Font Size

```php
use App\Models\GeneralSetting;

// Set to large for better accessibility
GeneralSetting::setFontSize(GeneralSetting::FONT_SIZE_LARGE);

// Or use string
GeneralSetting::setFontSize('extra_large');
```

### Example 3: Get Current Font Size Info

```php
$currentSize = GeneralSetting::getFontSize(); // 'medium'
$values = GeneralSetting::getFontSizeValues();

echo "Base font size: " . $values['base']; // 1rem
echo "Small font size: " . $values['sm'];  // 0.875rem
```

## How It Works

### 1. Database Storage
Font size preference is stored in the `general_settings` table:
```
key: font_size
value: medium (or small, large, extra_large)
display_name: System Font Size
group: appearance
```

### 2. View Composer
The `FontSizeComposer` automatically injects font size variables into all views:
```php
// Available in all views
$fontSizeValues  // Array of font sizes
$currentFontSize // Current setting
$fontSizeCss     // CSS variables string
```

### 3. CSS Variables
Font sizes are applied via CSS custom properties in the layout:
```css
:root {
    --font-size-base: 1rem;
    --font-size-sm: 0.875rem;
    --font-size-lg: 1.125rem;
    --font-size-xl: 1.25rem;
    --font-size-2xl: 1.5rem;
    --font-size-3xl: 1.875rem;
    --font-size-4xl: 2.25rem;
}
```

### 4. Automatic Application
All Tailwind text classes are overridden to use the CSS variables:
```css
.text-sm { font-size: var(--font-size-sm) !important; }
.text-base { font-size: var(--font-size-base) !important; }
.text-lg { font-size: var(--font-size-lg) !important; }
/* etc. */
```

## File Structure

```
app/
├── Models/
│   └── GeneralSetting.php          # Enhanced with font size methods
├── View/
│   └── Composers/
│       └── FontSizeComposer.php    # Injects font size into views
└── Providers/
    └── FontSizeServiceProvider.php # Registers view composer

resources/
└── views/
    └── layouts/
        └── app.blade.php            # Updated with font size CSS

bootstrap/
└── providers.php                    # FontSizeServiceProvider registered
```

## Database Schema

The `general_settings` table already exists with:
- `key` (string, unique)
- `value` (text, nullable)
- `display_name` (string, nullable)
- `group` (string, default: 'general')

No migration needed - uses existing table!

## Caching

- Settings are cached for 1 hour (3600 seconds)
- Cache is automatically cleared when settings are updated
- Manual cache clear: `GeneralSetting::clearCache()`

## Integration Steps

### 1. Add Routes (Optional)
```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/appearance', [SettingsController::class, 'appearance'])
        ->name('settings.appearance');
    Route::post('/settings/font-size', [SettingsController::class, 'updateFontSize'])
        ->name('settings.update-font-size');
});
```

### 2. Create Settings Controller (Optional)
```php
php artisan make:controller SettingsController
```

### 3. Create Settings View (Optional)
Create a settings page where users can change font size.

### 4. Set Default Font Size (Optional)
```php
// In a seeder or tinker
use App\Models\GeneralSetting;

GeneralSetting::setFontSize(GeneralSetting::FONT_SIZE_MEDIUM);
```

## Testing

### Test in Tinker
```php
php artisan tinker

// Set font size
\App\Models\GeneralSetting::setFontSize('large');

// Get current font size
\App\Models\GeneralSetting::getFontSize();

// Get font size values
\App\Models\GeneralSetting::getFontSizeValues();

// Get options for dropdown
\App\Models\GeneralSetting::getFontSizeOptions();
```

### Test in Browser
1. Visit any page in your application
2. Open browser DevTools
3. Check the `<style>` tag in `<head>` for CSS variables
4. Change font size setting
5. Refresh page - all text should scale

## Benefits

✅ **Accessibility**: Users can increase font size for better readability
✅ **Consistency**: All text scales proportionally
✅ **Performance**: Settings are cached
✅ **Flexibility**: Easy to add more font size options
✅ **No Code Changes**: Works with existing Tailwind classes
✅ **System-Wide**: One setting affects entire application

## Constants Reference

```php
use App\Models\GeneralSetting;

GeneralSetting::FONT_SIZE_SMALL        // 'small'
GeneralSetting::FONT_SIZE_MEDIUM       // 'medium'
GeneralSetting::FONT_SIZE_LARGE        // 'large'
GeneralSetting::FONT_SIZE_EXTRA_LARGE  // 'extra_large'
```

## Next Steps

To fully integrate this feature:

1. **Create Settings UI**: Build a settings page where admins can change font size
2. **Add Permission**: Restrict font size changes to admins only
3. **Add Preview**: Show font size preview before applying
4. **User Preferences**: Optionally allow per-user font size preferences
5. **Add More Settings**: Use the same pattern for other appearance settings (theme, colors, etc.)

## Notes

- The CSS lint warnings about `@tailwind` are normal and can be ignored
- Font sizes use `rem` units for accessibility (respects browser font size settings)
- The `!important` flag ensures font sizes override Tailwind defaults
- Changes take effect immediately on page refresh (no cache clearing needed for users)
