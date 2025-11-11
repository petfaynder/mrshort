<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LevelConfiguration;
use App\Models\GamificationReward;
use App\Models\GamificationGoal;
use App\Models\User;

class GamificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLevelConfigurations();
        $this->seedRewards();
        $this->seedGoals();
        $this->assignInitialLevelToUsers();
    }

    private function seedLevelConfigurations()
    {
        LevelConfiguration::firstOrCreate(['level' => 1], ['name' => 'Başlangıç', 'required_experience' => 0, 'rewards_description' => 'Temel seviye.']);
        LevelConfiguration::firstOrCreate(['level' => 2], ['name' => 'Bronz', 'required_experience' => 100, 'rewards_description' => 'Bronz seviye ödülleri.']);
        LevelConfiguration::firstOrCreate(['level' => 3], ['name' => 'Gümüş', 'required_experience' => 300, 'rewards_description' => 'Gümüş seviye ödülleri.']);
        LevelConfiguration::firstOrCreate(['level' => 4], ['name' => 'Altın', 'required_experience' => 700, 'rewards_description' => 'Altın seviye ödülleri.']);
        LevelConfiguration::firstOrCreate(['level' => 5], ['name' => 'Platin', 'required_experience' => 1500, 'rewards_description' => 'Platin seviye ödülleri.']);
        LevelConfiguration::firstOrCreate(['level' => 6], ['name' => 'Elmas', 'required_experience' => 3000, 'rewards_description' => 'Elmas seviye ödülleri.']);
        LevelConfiguration::firstOrCreate(['level' => 7], ['name' => 'Efsanevi', 'required_experience' => 6000, 'rewards_description' => 'Efsanevi seviye ödülleri.']);
    }

    private function seedRewards()
    {
        // Rozetler (Başarımlar)
        GamificationReward::firstOrCreate(['name' => 'İlk Kısaltan Rozeti'], [
            'description' => 'İlk linkini kısaltanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/first_shortener_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Sosyal Kelebek Rozeti'], [
            'description' => '100 link paylaşımı yapanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/social_butterfly_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Usta Kısaltıcı Rozeti'], [
            'description' => '1000 link kısaltanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/master_shortener_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Popüler İçerik Üreticisi Rozeti'], [
            'description' => '10.000 tıklama alanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/popular_creator_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Referans Ustası Rozeti'], [
            'description' => '10 yeni kullanıcı getirenlere verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/referral_master_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Ekonomi Uzmanı Rozeti'], [
            'description' => 'Toplamda $100 kazananlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/economy_expert_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Girişimci Rozeti'], [
            'description' => 'İlk reklam kampanyasını oluşturanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/entrepreneur_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Yardımsever Rozeti'], [
            'description' => 'İlk destek talebini gönderenlere verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/helper_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Sadık Kullanıcı Rozeti'], [
            'description' => '5 gün üst üste giriş yapanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/loyal_user_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => 'Profil Uzmanı Rozeti'], [
            'description' => 'Profilini %100 tamamlayanlara verilir.',
            'type' => 'badge',
            'value' => 0,
            'image_path' => 'rewards/profile_expert_badge.png',
            'is_active' => true,
            'reward_config' => null,
        ]);


        // Puan ve Coin Bonusları
        GamificationReward::firstOrCreate(['name' => '50 Puan Bonusu'], [
            'description' => 'Bir hedefi tamamladığınızda 50 puan kazanın.',
            'type' => 'points',
            'value' => 50,
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => '100 Coin Bonusu'], [
            'description' => 'Bir hedefi tamamladığınızda 100 coin kazanın.',
            'type' => 'virtual_currency',
            'value' => 100,
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => '250 Puan Bonusu'], [
            'description' => 'Büyük bir hedefi tamamladığınızda 250 puan kazanın.',
            'type' => 'points',
            'value' => 250,
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => '500 Coin Bonusu'], [
            'description' => 'Büyük bir hedefi tamamladığınızda 500 coin kazanın.',
            'type' => 'virtual_currency',
            'value' => 500,
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => '1000 Puan Bonusu'], [
            'description' => 'Çok büyük bir hedefi tamamladığınızda 1000 puan kazanın.',
            'type' => 'points',
            'value' => 1000,
            'is_active' => true,
            'reward_config' => null,
        ]);
        GamificationReward::firstOrCreate(['name' => '2000 Coin Bonusu'], [
            'description' => 'Çok büyük bir hedefi tamamladığınızda 2000 coin kazanın.',
            'type' => 'virtual_currency',
            'value' => 2000,
            'is_active' => true,
            'reward_config' => null,
        ]);

        // Yeni Ödül Türleri
        GamificationReward::firstOrCreate(['name' => 'Altın Avatar Paketi'], [
            'description' => 'Profilinizi kişiselleştirmek için özel avatar paketi.',
            'type' => 'avatar_item',
            'value' => 0,
            'image_path' => 'rewards/gold_avatar_pack.png',
            'is_active' => true,
            'reward_config' => [
                'avatars' => ['avatar1.png', 'avatar2.png'],
                'icon' => 'face', 'bg_color' => 'bg-yellow-100 dark:bg-yellow-900/50', 'icon_color' => 'text-yellow-500'
            ],
        ]);
        GamificationReward::firstOrCreate(['name' => 'Premium Tema: Gece Modu'], [
            'description' => 'Kullanıcı paneli için özel gece modu teması.',
            'type' => 'profile_theme',
            'value' => 0,
            'image_path' => 'rewards/dark_theme.png',
            'is_active' => true,
            'reward_config' => [
                'theme_name' => 'dark_mode',
                'icon' => 'palette', 'bg_color' => 'bg-purple-100 dark:bg-purple-900/50', 'icon_color' => 'text-purple-500'
            ],
        ]);
        GamificationReward::firstOrCreate(['name' => 'Reklam Önceliği (1 Hafta)'], [
            'description' => 'Linkleriniz 1 hafta boyunca %10 daha fazla gösterim alır.',
            'type' => 'ad_priority',
            'value' => 7, // Gün cinsinden süre
            'is_active' => true,
            'reward_config' => [
                'duration_days' => 7, 'priority_increase' => 10,
                'icon' => 'bolt', 'bg_color' => 'bg-blue-100 dark:bg-blue-900/50', 'icon_color' => 'text-blue-500'
            ],
        ]);
        GamificationReward::firstOrCreate(['name' => 'Premium Erişim (3 Gün)'], [
            'description' => '3 gün boyunca tüm premium özelliklere erişim.',
            'type' => 'premium_access',
            'value' => 3, // Gün cinsinden süre
            'is_active' => true,
            'reward_config' => [
                'duration_days' => 3,
                'icon' => 'verified_user', 'bg_color' => 'bg-green-100 dark:bg-green-900/50', 'icon_color' => 'text-green-500'
            ],
        ]);
        GamificationReward::firstOrCreate(['name' => '%25 İndirim Kuponu'], [
            'description' => 'Bir sonraki reklam kampanyanızda %25 indirim.',
            'type' => 'discount_coupon',
            'value' => 25, // Yüzde olarak indirim
            'is_active' => true,
            'reward_config' => [
                'discount_percentage' => 25, 'applies_to' => 'ad_campaign',
                'icon' => 'sell', 'bg_color' => 'bg-pink-100 dark:bg-pink-900/50', 'icon_color' => 'text-pink-500'
            ],
        ]);
        GamificationReward::firstOrCreate(['name' => 'XP Artırıcı (24 Saat)'], [
            'description' => '24 saat boyunca kazanılan deneyim puanlarını %50 artırır.',
            'type' => 'xp_booster',
            'value' => 50, // Yüzde olarak artış
            'is_active' => true,
            'reward_config' => [
                'duration_hours' => 24, 'boost_percentage' => 50,
                'icon' => 'local_fire_department', 'bg_color' => 'bg-red-100 dark:bg-red-900/50', 'icon_color' => 'text-red-500'
            ],
        ]);
        GamificationReward::firstOrCreate(['name' => 'Özel Profil Arka Planı: Galaksi'], [
            'description' => 'Profiliniz için özel galaksi temalı arka plan.',
            'type' => 'profile_theme',
            'value' => 0,
            'image_path' => 'rewards/galaxy_theme.png',
            'is_active' => true,
            'reward_config' => ['theme_name' => 'galaxy_theme'],
        ]);
        GamificationReward::firstOrCreate(['name' => '500 Puanlık Mağaza Kredisi'], [
            'description' => 'Mağazada harcamak üzere 500 puanlık kredi.',
            'type' => 'points',
            'value' => 500,
            'is_active' => true,
            'reward_config' => ['store_credit' => true],
        ]);
        GamificationReward::firstOrCreate(['name' => '1000 Coinlik Mağaza Kredisi'], [
            'description' => 'Mağazada harcamak üzere 1000 coinlik kredi.',
            'type' => 'virtual_currency',
            'value' => 1000,
            'is_active' => true,
            'reward_config' => ['store_credit' => true],
        ]);
    }

    private function seedGoals()
    {
        // Link Kısaltma Hedefleri (Toplam 10 hedef)
        $reward_50_points = GamificationReward::where('name', '50 Puan Bonusu')->first()->id;
        $reward_100_coins = GamificationReward::where('name', '100 Coin Bonusu')->first()->id;
        $reward_250_points = GamificationReward::where('name', '250 Puan Bonusu')->first()->id;
        $reward_500_coins = GamificationReward::where('name', '500 Coin Bonusu')->first()->id;
        $reward_1000_points = GamificationReward::where('name', '1000 Puan Bonusu')->first()->id;
        $reward_2000_coins = GamificationReward::where('name', '2000 Coin Bonusu')->first()->id;
        $reward_first_shortener = GamificationReward::where('name', 'İlk Kısaltan Rozeti')->first()->id;
        $reward_master_shortener = GamificationReward::where('name', 'Usta Kısaltıcı Rozeti')->first()->id;
        $reward_popular_creator = GamificationReward::where('name', 'Popüler İçerik Üreticisi Rozeti')->first()->id;
        $reward_social_butterfly = GamificationReward::where('name', 'Sosyal Kelebek Rozeti')->first()->id;
        $reward_referral_master = GamificationReward::where('name', 'Referans Ustası Rozeti')->first()->id;
        $reward_economy_expert = GamificationReward::where('name', 'Ekonomi Uzmanı Rozeti')->first()->id;
        $reward_entrepreneur = GamificationReward::where('name', 'Girişimci Rozeti')->first()->id;
        $reward_helper = GamificationReward::where('name', 'Yardımsever Rozeti')->first()->id;
        $reward_loyal_user = GamificationReward::where('name', 'Sadık Kullanıcı Rozeti')->first()->id;
        $reward_profile_expert = GamificationReward::where('name', 'Profil Uzmanı Rozeti')->first()->id;
        $reward_premium_access_3_days = GamificationReward::where('name', 'Premium Erişim (3 Gün)')->first()->id;
        $reward_ad_priority_1_week = GamificationReward::where('name', 'Reklam Önceliği (1 Hafta)')->first()->id;
        $reward_discount_25_percent = GamificationReward::where('name', '%25 İndirim Kuponu')->first()->id;
        $reward_xp_booster_24_hours = GamificationReward::where('name', 'XP Artırıcı (24 Saat)')->first()->id;
        $reward_gold_avatar_pack = GamificationReward::where('name', 'Altın Avatar Paketi')->first()->id;
        $reward_dark_theme = GamificationReward::where('name', 'Premium Tema: Gece Modu')->first()->id;
        $reward_galaxy_theme = GamificationReward::where('name', 'Özel Profil Arka Planı: Galaksi')->first()->id;
        $reward_500_points_credit = GamificationReward::where('name', '500 Puanlık Mağaza Kredisi')->first()->id;
        $reward_1000_coins_credit = GamificationReward::where('name', '1000 Coinlik Mağaza Kredisi')->first()->id;


        GamificationGoal::firstOrCreate(['title' => 'İlk Linkini Kısalt'], [
            'description' => 'Platformdaki ilk linkini kısaltarak bu başarıyı elde et.',
            'type' => 'shorten_links', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_first_shortener, 'points' => 10, 'coins' => 5, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5 Link Kısalt'], [
            'description' => 'Toplamda 5 link kısaltarak küçük bir adım at.',
            'type' => 'shorten_links', 'target_value' => 5, 'difficulty_level' => 'easy', 'category' => 'daily', 'is_active' => true,
            'reward_id' => $reward_50_points, 'points' => 15, 'coins' => 8, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '10 Link Kısalt'], [
            'description' => 'Toplamda 10 link kısaltarak bu hedefi tamamla.',
            'type' => 'shorten_links', 'target_value' => 10, 'difficulty_level' => 'easy', 'category' => 'daily', 'is_active' => true,
            'reward_id' => $reward_100_coins, 'points' => 20, 'coins' => 10, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '25 Link Kısalt'], [
            'description' => 'Toplamda 25 link kısaltarak ilerlemeni göster.',
            'type' => 'shorten_links', 'target_value' => 25, 'difficulty_level' => 'medium', 'category' => 'weekly', 'is_active' => true,
            'points' => 30, 'coins' => 15, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '50 Link Kısalt'], [
            'description' => 'Toplamda 50 link kısaltarak bir sonraki seviyeye yaklaş.',
            'type' => 'shorten_links', 'target_value' => 50, 'difficulty_level' => 'medium', 'category' => 'weekly', 'is_active' => true,
            'reward_id' => $reward_250_points, 'points' => 40, 'coins' => 20, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '100 Link Kısalt'], [
            'description' => 'Toplamda 100 link kısaltarak Usta Kısaltıcı rozetini kazan.',
            'type' => 'shorten_links', 'target_value' => 100, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_master_shortener, 'points' => 50, 'coins' => 25, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '250 Link Kısalt'], [
            'description' => 'Toplamda 250 link kısaltarak daha fazla deneyim kazan.',
            'type' => 'shorten_links', 'target_value' => 250, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'points' => 75, 'coins' => 35, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '500 Link Kısalt'], [
            'description' => 'Toplamda 500 link kısaltarak büyük bir başarıya imza at.',
            'type' => 'shorten_links', 'target_value' => 500, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_500_coins, 'points' => 100, 'coins' => 50, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '1000 Link Kısalt'], [
            'description' => 'Toplamda 1000 link kısaltarak efsanevi kısaltıcı ol.',
            'type' => 'shorten_links', 'target_value' => 1000, 'difficulty_level' => 'expert', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_1000_points, 'points' => 200, 'coins' => 100, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5000 Link Kısalt'], [
            'description' => 'Toplamda 5000 link kısaltarak gerçek bir usta olduğunu kanıtla.',
            'type' => 'shorten_links', 'target_value' => 5000, 'difficulty_level' => 'expert', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_2000_coins, 'points' => 500, 'coins' => 250, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '10000 Link Kısalt'], [
            'description' => 'Toplamda 10000 link kısaltarak kısaltma imparatoru ol.',
            'type' => 'shorten_links', 'target_value' => 10000, 'difficulty_level' => 'legendary', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_premium_access_3_days, 'points' => 1000, 'coins' => 500, 'goal_type_config' => null,
        ]);

        // Tıklama Alma Hedefleri (Toplam 10 hedef)
        GamificationGoal::firstOrCreate(['title' => 'İlk 10 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 10 tıklama al.',
            'type' => 'clicks', 'target_value' => 10, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'points' => 15, 'coins' => 8, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '50 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 50 tıklama al.',
            'type' => 'clicks', 'target_value' => 50, 'difficulty_level' => 'easy', 'category' => 'weekly', 'is_active' => true,
            'points' => 30, 'coins' => 15, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '100 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 100 tıklama al.',
            'type' => 'clicks', 'target_value' => 100, 'difficulty_level' => 'medium', 'category' => 'daily', 'is_active' => true,
            'points' => 50, 'coins' => 25, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '500 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 500 tıklama al.',
            'type' => 'clicks', 'target_value' => 500, 'difficulty_level' => 'medium', 'category' => 'weekly', 'is_active' => true,
            'points' => 75, 'coins' => 40, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '1000 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 1000 tıklama alarak Popüler İçerik Üreticisi rozetini kazan.',
            'type' => 'clicks', 'target_value' => 1000, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_popular_creator, 'points' => 100, 'coins' => 50, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5000 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 5000 tıklama alarak büyük bir kitleye ulaş.',
            'type' => 'clicks', 'target_value' => 5000, 'difficulty_level' => 'expert', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_ad_priority_1_week, 'points' => 250, 'coins' => 125, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '10000 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 10000 tıklama alarak içerik krallığını ilan et.',
            'type' => 'clicks', 'target_value' => 10000, 'difficulty_level' => 'expert', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_xp_booster_24_hours, 'points' => 500, 'coins' => 250, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '50000 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 50000 tıklama alarak efsanevi bir başarıya ulaş.',
            'type' => 'clicks', 'target_value' => 50000, 'difficulty_level' => 'legendary', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_500_coins, 'points' => 1000, 'coins' => 500, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '100000 Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 100000 tıklama alarak zirveye yerleş.',
            'type' => 'clicks', 'target_value' => 100000, 'difficulty_level' => 'legendary', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_1000_coins_credit, 'points' => 2000, 'coins' => 1000, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '1 Milyon Tıklama Al'], [
            'description' => 'Kısaltılmış linklerinden toplam 1 milyon tıklama alarak tarihe geç.',
            'type' => 'clicks', 'target_value' => 1000000, 'difficulty_level' => 'legendary', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_gold_avatar_pack, 'points' => 5000, 'coins' => 2500, 'goal_type_config' => null,
        ]);

        // Sosyal Paylaşım Hedefleri (Toplam 5 hedef)
        GamificationGoal::firstOrCreate(['title' => 'İlk Linkini Paylaş'], [
            'description' => 'Platformdaki ilk linkini sosyal medyada paylaş.',
            'type' => 'shares', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'points' => 10, 'coins' => 5, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5 Linki Sosyal Medyada Paylaş'], [
            'description' => '5 farklı linkini sosyal medya platformlarında paylaş.',
            'type' => 'shares', 'target_value' => 5, 'difficulty_level' => 'easy', 'category' => 'daily', 'is_active' => true,
            'reward_id' => $reward_social_butterfly, 'points' => 25, 'coins' => 10, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '10 Linki Sosyal Medyada Paylaş'], [
            'description' => '10 farklı linkini sosyal medyada paylaşarak daha fazla kişiye ulaş.',
            'type' => 'shares', 'target_value' => 10, 'difficulty_level' => 'medium', 'category' => 'weekly', 'is_active' => true,
            'points' => 40, 'coins' => 20, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '50 Linki Sosyal Medyada Paylaş'], [
            'description' => 'Toplamda 50 linkini sosyal medyada paylaşarak sosyal kelebek ol.',
            'type' => 'shares', 'target_value' => 50, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'points' => 75, 'coins' => 30, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '100 Linki Sosyal Medyada Paylaş'], [
            'description' => 'Toplamda 100 linkini sosyal medyada paylaşarak gerçek bir sosyal medya gurusu ol.',
            'type' => 'shares', 'target_value' => 100, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_dark_theme, 'points' => 150, 'coins' => 75, 'goal_type_config' => null,
        ]);

        // Referans Getirme Hedefleri (Toplam 5 hedef)
        GamificationGoal::firstOrCreate(['title' => 'İlk Referansını Getir'], [
            'description' => 'Platforma ilk referansını getirerek bu başarıyı elde et.',
            'type' => 'referrals', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'points' => 20, 'coins' => 10, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '3 Yeni Kullanıcı Getir'], [
            'description' => 'Referans linkinle 3 yeni kullanıcı getir.',
            'type' => 'referrals', 'target_value' => 3, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_100_coins, 'points' => 75, 'coins' => 150, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5 Yeni Kullanıcı Getir'], [
            'description' => 'Referans linkinle 5 yeni kullanıcı getirerek ağını genişlet.',
            'type' => 'referrals', 'target_value' => 5, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'points' => 100, 'coins' => 200, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '10 Yeni Kullanıcı Getir'], [
            'description' => 'Referans linkinle 10 yeni kullanıcı getirerek Referans Ustası rozetini kazan.',
            'type' => 'referrals', 'target_value' => 10, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_referral_master, 'points' => 150, 'coins' => 300, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => 'Referansların 100 Tıklama Alsın'], [
            'description' => 'Referansların kısaltılmış linklerinden toplam 100 tıklama almasını sağla.',
            'type' => 'referrals', 'target_value' => 100, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'points' => 120, 'coins' => 60, 'goal_type_config' => ['sub_goal_type' => 'referred_clicks'],
        ]);

        // Gelir Elde Etme Hedefleri (Toplam 5 hedef)
        GamificationGoal::firstOrCreate(['title' => 'İlk $1 Kazan'], [
            'description' => 'Platformda ilk $1 gelirini elde et.',
            'type' => 'earn_money', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'points' => 20, 'coins' => 10, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '$5 Kazan'], [
            'description' => 'Platformda toplam $5 gelir elde et.',
            'type' => 'earn_money', 'target_value' => 5, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'points' => 40, 'coins' => 20, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '$10 Kazan'], [
            'description' => 'Platformda toplam $10 gelir elde et.',
            'type' => 'earn_money', 'target_value' => 10, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'points' => 50, 'coins' => 25, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '$50 Kazan'], [
            'description' => 'Platformda toplam $50 gelir elde et.',
            'type' => 'earn_money', 'target_value' => 50, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'points' => 100, 'coins' => 50, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '$100 Kazan'], [
            'description' => 'Platformda toplam $100 gelir elde ederek Ekonomi Uzmanı rozetini kazan.',
            'type' => 'earn_money', 'target_value' => 100, 'difficulty_level' => 'hard', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_economy_expert, 'points' => 200, 'coins' => 100, 'goal_type_config' => null,
        ]);

        // Site İçi Etkileşim Hedefleri (Toplam 10 hedef)
        GamificationGoal::firstOrCreate(['title' => 'Profilini Tamamla'], [
            'description' => 'Profilindeki tüm gerekli bilgileri doldur.',
            'type' => 'profile_completion', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_profile_expert, 'points' => 10, 'coins' => 5, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => 'İlk Destek Talebini Gönder'], [
            'description' => 'İlk destek talebini göndererek platformu keşfet.',
            'type' => 'support_ticket', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_helper, 'points' => 5, 'coins' => 2, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5 Gün Üst Üste Giriş Yap'], [
            'description' => '5 gün boyunca her gün platforma giriş yap.',
            'type' => 'daily_login', 'target_value' => 5, 'difficulty_level' => 'medium', 'category' => 'weekly', 'is_active' => true,
            'reward_id' => $reward_loyal_user, 'points' => 25, 'coins' => 10, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => 'İlk Reklam Kampanyanı Oluştur'], [
            'description' => 'Platformda ilk reklam kampanyanı oluştur.',
            'type' => 'create_ad_campaign', 'target_value' => 1, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_entrepreneur, 'points' => 50, 'coins' => 25, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '3 Farklı Aracı Kullan'], [
            'description' => 'Toplu Kısaltıcı, Tam Sayfa Script gibi 3 farklı aracı kullan.',
            'type' => 'use_tools', 'target_value' => 3, 'difficulty_level' => 'medium', 'category' => 'one_time', 'is_active' => true,
            'points' => 30, 'coins' => 15, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => 'Liderlik Tablosunda İlk 10\'a Gir'], [
            'description' => 'Liderlik tablosunda ilk 10 arasına girerek yeteneğini kanıtla.',
            'type' => 'leaderboard_top_10', 'target_value' => 1, 'difficulty_level' => 'hard', 'category' => 'weekly', 'is_active' => true,
            'reward_id' => $reward_500_coins, 'points' => 100, 'coins' => 50, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => 'Bir Ay Boyunca Günlük Giriş Yap'], [
            'description' => 'Bir ay boyunca her gün platforma giriş yap.',
            'type' => 'daily_login', 'target_value' => 30, 'difficulty_level' => 'hard', 'category' => 'monthly', 'is_active' => true,
            'reward_id' => $reward_xp_booster_24_hours, 'points' => 150, 'coins' => 75, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '5 Farklı Ülkeye Link Paylaş'], [
            'description' => 'Linklerini 5 farklı ülkeden kullanıcıların tıklamasını sağla.',
            'type' => 'clicks_from_countries', 'target_value' => 5, 'difficulty_level' => 'expert', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_galaxy_theme, 'points' => 200, 'coins' => 100, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => '10 Farklı Kategoriye Link Kısalt'], [
            'description' => '10 farklı link kategorisinde link kısalt.',
            'type' => 'shorten_links_categories', 'target_value' => 10, 'difficulty_level' => 'expert', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_500_points_credit, 'points' => 250, 'coins' => 125, 'goal_type_config' => null,
        ]);
        GamificationGoal::firstOrCreate(['title' => 'Premium Üyeliği Dene'], [
            'description' => 'Premium üyeliği deneyerek tüm özelliklere eriş.',
            'type' => 'try_premium', 'target_value' => 1, 'difficulty_level' => 'easy', 'category' => 'one_time', 'is_active' => true,
            'reward_id' => $reward_discount_25_percent, 'points' => 50, 'coins' => 25, 'goal_type_config' => null,
        ]);


        // Örnek bir kullanıcıya başlangıç seviyesi atama (varsa)
        $user = User::first();
        if ($user && !$user->userLevel) {
            $user->userLevel()->firstOrCreate(['user_id' => $user->id], ['level' => 1, 'experience_points' => 0]);
        }
    }

    private function assignInitialLevelToUsers()
    {
        $user = User::first();
        if ($user && !$user->userLevel) {
            $user->userLevel()->firstOrCreate(['user_id' => $user->id], ['level' => 1, 'experience_points' => 0]);
        }
    }
}
