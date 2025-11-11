<?php

namespace App\Enums;

enum AdType: string
{
    case Banner = 'banner';
    case Interstitial = 'interstitial';
    case Popup = 'popup';
    case Html = 'html'; // Added
    case ThirdParty = 'third_party';

    /**
     * Enum değerinin okunabilir etiketini getir
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Banner => 'Banner Reklamı',
            self::Interstitial => 'Geçiş Reklamı',
            self::Popup => 'Pop-up Reklamı',
            self::Html => 'HTML Reklamı', // Added
            self::ThirdParty => 'Üçüncü Parti Kodu',
        };
    }

    /**
     * Tüm enum değerlerini etiketli array olarak getir
     */
    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }
        return $options;
    }
}
