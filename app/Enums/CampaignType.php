<?php

namespace App\Enums;

enum CampaignType: string
{
    case Admin = 'admin';
    case User = 'user';

    /**
     * Enum değerinin okunabilir etiketini getir
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Admin => 'Admin Kampanyası',
            self::User => 'Kullanıcı Kampanyası',
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