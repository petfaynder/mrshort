<?php

namespace App\Enums;

enum StepType: string
{
    case Interstitial = 'interstitial';
    case BannerPage = 'banner_page';

    /**
     * Enum değerinin okunabilir etiketini getir
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Interstitial => 'Geçiş Reklamı',
            self::BannerPage => 'Banner Sayfası',
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