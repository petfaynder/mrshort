<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GamificationReward;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Link Milestones
            [
                'name' => 'İlk Kısaltma',
                'description' => 'İlk linkinizi kısalttınız!',
                'type' => 'badge',
                'value' => 'first_link',
                'is_active' => true,
            ],
            [
                'name' => 'Sosyal Paylaşımcı',
                'description' => '10 farklı platformda link paylaştınız',
                'type' => 'badge',
                'value' => 'social_sharer',
                'is_active' => true,
            ],
            [
                'name' => 'Usta Kısaltıcı',
                'description' => '100 link kısalttınız!',
                'type' => 'badge',
                'value' => 'master_shortener',
                'is_active' => true,
            ],
            [
                'name' => 'Popüler Üretici',
                'description' => 'Bir linkiniz 1.000 tıklama aldı',
                'type' => 'badge',
                'value' => 'popular_creator',
                'is_active' => true,
            ],
            [
                'name' => 'Referans Kralı',
                'description' => '10 kişiyi sisteme davet ettiniz',
                'type' => 'badge',
                'value' => 'referral_king',
                'is_active' => true,
            ],
            [
                'name' => 'Ekonomist',
                'description' => 'Toplam $10 kazandınız',
                'type' => 'badge',
                'value' => 'economist',
                'is_active' => true,
            ],
            [
                'name' => 'Girişimci',
                'description' => 'İlk reklam kampanyanızı oluşturdunuz',
                'type' => 'badge',
                'value' => 'entrepreneur',
                'is_active' => true,
            ],
            [
                'name' => 'Yardımsever',
                'description' => 'Destek ekibine geri bildirim gönderdiniz',
                'type' => 'badge',
                'value' => 'helper',
                'is_active' => true,
            ],
            [
                'name' => 'Sadık Kullanıcı',
                'description' => '30 günlük seri yaptınız!',
                'type' => 'badge',
                'value' => 'loyal_user',
                'is_active' => true,
            ],
            [
                'name' => 'Profil Uzmanı',
                'description' => 'Profilinizi tamamen doldurdunuz',
                'type' => 'badge',
                'value' => 'profile_expert',
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            GamificationReward::updateOrCreate(
                ['value' => $badge['value'], 'type' => 'badge'],
                $badge
            );
        }
    }
}
