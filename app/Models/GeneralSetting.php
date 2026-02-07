<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GeneralSetting extends Model
{
    protected $fillable = ['key', 'value', 'display_name', 'group'];

    // Font size constants
    const FONT_SIZE_SMALL = 'small';
    const FONT_SIZE_MEDIUM = 'medium';
    const FONT_SIZE_LARGE = 'large';
    const FONT_SIZE_EXTRA_LARGE = 'extra_large';

    // Font size mappings (in rem units)
    const FONT_SIZES = [
        self::FONT_SIZE_SMALL => [
            'base' => '0.875rem',      // 14px
            'sm' => '0.8125rem',       // 13px
            'lg' => '1rem',            // 16px
            'xl' => '1.125rem',        // 18px
            '2xl' => '1.25rem',        // 20px
            '3xl' => '1.5rem',         // 24px
            '4xl' => '1.875rem',       // 30px
        ],
        self::FONT_SIZE_MEDIUM => [
            'base' => '1rem',          // 16px (default)
            'sm' => '0.875rem',        // 14px
            'lg' => '1.125rem',        // 18px
            'xl' => '1.25rem',         // 20px
            '2xl' => '1.5rem',         // 24px
            '3xl' => '1.875rem',       // 30px
            '4xl' => '2.25rem',        // 36px
        ],
        self::FONT_SIZE_LARGE => [
            'base' => '1.125rem',      // 18px
            'sm' => '1rem',            // 16px
            'lg' => '1.25rem',         // 20px
            'xl' => '1.5rem',          // 24px
            '2xl' => '1.75rem',        // 28px
            '3xl' => '2.25rem',        // 36px
            '4xl' => '2.75rem',        // 44px
        ],
        self::FONT_SIZE_EXTRA_LARGE => [
            'base' => '1.25rem',       // 20px
            'sm' => '1.125rem',        // 18px
            'lg' => '1.5rem',          // 24px
            'xl' => '1.75rem',         // 28px
            '2xl' => '2rem',           // 32px
            '3xl' => '2.5rem',         // 40px
            '4xl' => '3rem',           // 48px
        ],
    ];

    /**
     * Get a setting value by key with optional caching
     */
    public static function getValue($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key
     */
    public static function setValue($key, $value, $displayName = null, $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'display_name' => $displayName ?? $key,
                'group' => $group,
            ]
        );

        // Clear cache
        Cache::forget("setting_{$key}");

        return $setting;
    }

    /**
     * Get current font size setting
     */
    public static function getFontSize()
    {
        return self::getValue('font_size', self::FONT_SIZE_MEDIUM);
    }

    /**
     * Set font size setting
     */
    public static function setFontSize($size)
    {
        if (!in_array($size, [self::FONT_SIZE_SMALL, self::FONT_SIZE_MEDIUM, self::FONT_SIZE_LARGE, self::FONT_SIZE_EXTRA_LARGE])) {
            throw new \InvalidArgumentException("Invalid font size: {$size}");
        }

        return self::setValue('font_size', $size, 'System Font Size', 'appearance');
    }

    /**
     * Get font size values for current setting
     */
    public static function getFontSizeValues()
    {
        $currentSize = self::getFontSize();
        return self::FONT_SIZES[$currentSize] ?? self::FONT_SIZES[self::FONT_SIZE_MEDIUM];
    }

    /**
     * Get all available font size options
     */
    public static function getFontSizeOptions()
    {
        return [
            self::FONT_SIZE_SMALL => 'Small',
            self::FONT_SIZE_MEDIUM => 'Medium (Default)',
            self::FONT_SIZE_LARGE => 'Large',
            self::FONT_SIZE_EXTRA_LARGE => 'Extra Large',
        ];
    }

    /**
     * Get CSS variables for current font size
     */
    public static function getFontSizeCssVariables()
    {
        $sizes = self::getFontSizeValues();
        $css = '';

        foreach ($sizes as $key => $value) {
            $css .= "--font-size-{$key}: {$value};\n";
        }

        return $css;
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
    }
}
