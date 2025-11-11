<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Örnek statik sayfaları ekle
        \App\Models\Page::create([
            'slug' => 'hakkimizda',
            'title' => 'Hakkımızda',
            'content' => '<p>Bu site, link kısaltma hizmeti sunmaktadır.</p>',
        ]);

        \App\Models\Page::create([
            'slug' => 'gizlilik-politikasi',
            'title' => 'Gizlilik Politikası',
            'content' => '<p>Gizlilik politikamız burada yer almaktadır.</p>',
        ]);

        \App\Models\Page::create([
            'slug' => 'kullanim-sartlari',
            'title' => 'Kullanım Şartları',
            'content' => '<p>Kullanım şartları burada yer almaktadır.</p>',
        ]);
    }
}
