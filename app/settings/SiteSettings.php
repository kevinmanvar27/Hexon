<?php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_name;
    public ?string $logo; // Nullable in case no logo is uploaded

    public static function group(): string
    {
        return 'site';
    }
}
    