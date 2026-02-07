# Font Size Setting - User Guide

## How to Change Font Size

### Step 1: Navigate to Settings
1. Log in to your ERP system
2. Click on **Settings** in the sidebar menu
3. You will see the System Settings page

### Step 2: Find Font Size Setting
1. Look for the **Appearance Settings** section
2. Find the **System Font Size** dropdown

### Step 3: Select Your Preferred Size
Choose from 4 available options:
- **Small** - Smaller text (14px base) - Good for fitting more content
- **Medium (Default)** - Standard text (16px base) - Recommended for most users
- **Large** - Larger text (18px base) - Better readability
- **Extra Large** - Largest text (20px base) - Best for accessibility

### Step 4: Save Changes
1. Click the **Save All Settings** button at the bottom
2. You'll see a success message
3. **Refresh the page** (Press F5 or Ctrl+R / Cmd+R)
4. All text across the entire system will now use your selected font size!

## What Changes?

When you change the font size, **ALL** text elements across the entire system will scale:
- Navigation menus
- Page headings
- Body text
- Buttons
- Tables
- Forms
- Cards
- Everything!

## Tips

💡 **Accessibility**: Use Large or Extra Large if you have difficulty reading small text
💡 **Screen Space**: Use Small if you want to see more content on screen
💡 **Default**: Medium is recommended for most users
💡 **Instant Preview**: After saving, just refresh any page to see the changes

## Troubleshooting

**Q: I changed the font size but don't see any difference**
A: Make sure to refresh the page (F5) after saving settings

**Q: Can I change font size for just one page?**
A: No, the font size setting applies system-wide to maintain consistency

**Q: Can different users have different font sizes?**
A: Currently, the font size is a system-wide setting that applies to all users

**Q: The text is too large/small**
A: Simply go back to Settings and select a different size, then save and refresh

## For Administrators

The font size setting is stored in the `general_settings` table with:
- **Key**: `font_size`
- **Value**: `small`, `medium`, `large`, or `extra_large`
- **Group**: `appearance`

You can also change it programmatically:
```php
use App\Models\GeneralSetting;

GeneralSetting::setFontSize('large');
```
