<?php

namespace App\View\Composers;

use App\Models\GeneralSetting;
use Illuminate\View\View;

class FontSizeComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $fontSizeValues = GeneralSetting::getFontSizeValues();
        $currentFontSize = GeneralSetting::getFontSize();
        
        $view->with([
            'fontSizeValues' => $fontSizeValues,
            'currentFontSize' => $currentFontSize,
            'fontSizeCss' => GeneralSetting::getFontSizeCssVariables(),
            'primaryColorCss' => GeneralSetting::getPrimaryColorCssVariable(),
        ]);
    }
}
