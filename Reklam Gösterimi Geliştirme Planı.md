# Link Geçiş Sayfalarında Reklam Gösterimi Geliştirme Planı

Bu belge, link kısaltma servisindeki link geçiş sayfalarında admin ve kullanıcılar tarafından yönetilebilen, farklı türlerde ve hedeflenebilir reklamların gösterilmesi özelliğinin geliştirilmesi için adım adım bir plan sunmaktadır.

## Mevcut Durum Analizi

*   Mevcut sistemde temel link kısaltma ve yönlendirme (`LinkController`) bulunmaktadır.
*   Genel reklam ayarları için `AdSetting` modeli ve basit bir reklam geçiş view'i (`ad_interstitial.blade.php`) mevcuttur.
*   Admin paneli için Filament, kullanıcı paneli için mevcut bir yapı kullanılmaktadır.
*   Temel tıklama takibi (`LinkClick`) yapılmaktadır.

## Hedeflenen Özellikler

*   Admin panelinden banner, interstitial ve pop-up türü reklam kampanyaları oluşturulabilmesi.
*   Adminin üçüncü parti reklam kodlarını sisteme ekleyebilmesi.
*   Adminin reklam geçiş sayfasının adım sayısını ve her adımda gösterilecek reklamları belirleyebilmesi.
*   Adminin pop-up reklamların hangi adımlarda gösterileceğini belirleyebilmesi.
*   Kullanıcıların user panelden kendi banner, interstitial ve pop-up reklam kampanyalarını oluşturabilmesi.
*   Kullanıcıların reklamlarını ülke, cihaz gibi özelliklere göre hedefleyebilmesi.
*   Kullanıcı pop-up reklamları için sadece hedef URL girebilmesi (güvenlik nedeniyle kod girişi olmaması).
*   Admin ve kullanıcı tarafından oluşturulan kampanyaların ağırlıklandırma stratejisine göre uyumlu şekilde çalışması.
*   Her kampanya ve reklam için temel istatistiklerin (gösterim, tıklama) takip edilmesi.

## Veritabanı Şeması

Aşağıdaki veritabanı şeması, reklam kampanyalarını, adımlarını ve reklam içeriklerini saklamak için kullanılacaktır:

```mermaid
erDiagram
    users ||--o{ ad_campaigns : "oluşturdu"
    ad_campaigns {
        int id PK
        int user_id FK "null olabilir (admin için)"
        string name
        string campaign_type ENUM("admin", "user")
        boolean is_active
        json targeting_rules "ülke, cihaz, trafik miktarı vb."
        int total_impressions DEFAULT 0
        int total_clicks DEFAULT 0
        datetime created_at
        datetime updated_at
    }

    ad_campaigns ||--o{ ad_steps : "içerir"
    ad_steps {
        int id PK
        int ad_campaign_id FK
        int step_number
        string step_type ENUM("interstitial", "banner_page") "Geçiş sayfası türü"
        int wait_time "Saniye cinsinden bekleme süresi"
        boolean show_popup "Bu adımda pop-up gösterilsin mi?"
        datetime created_at
        datetime updated_at
    }

    ad_steps ||--o{ step_ads : "gösterir"
    step_ads {
        int id PK
        int ad_step_id FK
        string ad_type ENUM("banner", "interstitial", "popup", "third_party")
        text ad_code "Reklam kodu veya içeriği"
        json ad_settings "Reklama özel ayarlar (boyut, format, kullanıcı pop-up URL'si vb.)"
        int impressions DEFAULT 0
        int clicks DEFAULT 0
        datetime created_at
        datetime updated_at
    }
```

Ayrıca, admin ve kullanıcı kampanyaları için ağırlıkları saklamak amacıyla `ad_settings` tablosuna veya yeni bir ayarlar tablosuna alanlar eklenecektir (örneğin `admin_campaign_weight`, `user_campaign_weight`, `admin_popup_weight`, `user_popup_weight`).

## İş Akışı ve Mantık

Kullanıcı bir kısa linke tıkladığında izlenecek temel iş akışı:

```mermaid
graph TD
    A[Kısa Linke Tıklandı] --> B{Kullanıcı Bilgileri Alındı};
    B --> C[Aktif Kampanyalar Çekildi];
    C --> D{Hedeflemeye Uyan Admin Kampanyaları Filtrelendi};
    C --> E{Hedeflemeye Uyan Kullanıcı Kampanyaları Filtrelendi};
    D & E --> F{Ağırlıklandırma ile Birincil Kampanya Seçildi};
    F --> G[Seçilen Kampanyanın Adımları Alındı];
    G --> H{İlk Adıma Yönlendir};
    H --> I[Reklam Adımı View'i Gösterildi];
    I --> J{Adımda Pop-up Gösterilecek mi? (show_popup)};
    J -- Evet --> K{Uygun Pop-up Kampanyaları Filtrelendi};
    K --> L{Pop-up Ağırlıklandırma ile Pop-up Kampanyası Seçildi};
    L --> M[Seçilen Pop-up Kampanyasından Rastgele Pop-up Reklam Seçildi];
    M --> N[Pop-up Reklamı Göster];
    J -- Hayır --> P[Diğer Reklamları Göster]; %% Pop-up yoksa
    N --> P;
    P --> Q{Bekleme Süresi Doldu?};
    Q -- Hayır --> P;
    Q -- Evet --> R{Başka Adım Var mı?};
    R -- Evet --> S[Bir Sonraki Adıma Yönlendir];
    R -- Hayır --> T[Orijinal Linke Yönlendir];
    S --> I;
    T --> U[Hedefe Ulaşıldı];
```

**Kampanya Önceliklendirme (Ağırlıklandırma):**

Hedeflemeye uyan birden fazla aktif kampanya olduğunda, admin panelinde belirlenen ağırlıklara göre (örneğin %70 admin, %30 kullanıcı) rastgele seçim yapılarak birincil kampanya belirlenir. Eğer bir kategoride uygun kampanya yoksa, diğer kategorideki uygun kampanyalar arasından seçim yapılır.

**Pop-up Gösterimi:**

Bir reklam adımında pop-up gösterilmesine karar verilmişse (`show_popup = true`), uygun admin ve kullanıcı pop-up kampanyaları arasından, pop-up'lara özel ağırlıklara göre rastgele bir pop-up kampanyası seçilir ve bu kampanyadan rastgele bir pop-up reklam gösterilir. Kullanıcı pop-up'ları için `ad_settings`'teki URL kullanılır.

**Hedefleme Kuralları Uygulaması:**

`ad_campaigns.targeting_rules` JSON alanındaki kurallar (ülke, cihaz, OS, tarayıcı vb.), kullanıcının gerçek zamanlı bilgilerine göre değerlendirilir. Bir kampanya, ancak tüm tanımlı kurallara uyuyorsa uygun kabul edilir.

## Geliştirme Adımları

1.  **Veritabanı Migrasyonları:** Gerekli tabloları ve alanları oluşturacak migrasyon dosyalarını hazırlama ve çalıştırma.
2.  **Modeller:** `AdCampaign`, `AdStep`, `StepAd` modellerini ve ilişkilerini tanımlama. JSON cast işlemlerini ekleme.
3.  **Admin Paneli (Filament):**
    *   `AdCampaignResource`, `AdStepResource`, `StepAdResource` kaynaklarını oluşturma.
    *   Form ve tablo şemalarını tasarlama, ilişki yöneticilerini ekleme.
    *   Hedefleme kuralları ve reklam içeriği/kodu giriş alanlarını (türe göre farklılaşan) ekleme.
    *   Genel reklam ağırlıkları için ayarlar sayfasını oluşturma.
4.  **Kullanıcı Paneli:**
    *   "Reklamlarım" menü öğesini ekleme.
    *   Kampanya listeleme, oluşturma, düzenleme, silme ve istatistik görüntüleme arayüzlerini geliştirme (Livewire veya benzeri ile).
    *   Kullanıcı pop-up reklamları için URL girişini ve `ad_settings`'e kaydetme mantığını uygulama.
5.  **Link Kontrolcüsü Güncellemesi:**
    *   [`LinkController::redirect()`](app/Http/Controllers/LinkController.php:33) metodunda kullanıcı bilgisi toplama, kampanya filtreleme, ağırlıklandırma ile kampanya seçimi ve yeni adım rotasına yönlendirme mantığını ekleme.
    *   Yeni `LinkController::showAdStep` metodunu oluşturma.
6.  **View Dosyaları:**
    *   Mevcut `ad_interstitial.blade.php`'yi çok adımlı yapıya uygun hale getirme.
    *   `ad_banner_page.blade.php` gibi yeni view dosyaları oluşturma ve tanımlanmış reklam alanlarını ekleme.
    *   View'lerde reklam kodlarını/içeriklerini dinamik olarak gösterme mantığını uygulama.
    *   JavaScript ile geri sayım ve yönlendirme mantığını ekleme.
7.  **Rota Tanımlamaları:** Yeni adım rotalarını `routes/web.php`'ye ekleme.
8.  **İstatistik Takibi:** Gösterim ve tıklama sayılarını artırma mantığını `LinkController` ve view'lerde uygulama.
9.  **Test ve Optimizasyon:** Tüm özelliklerin doğru çalıştığından emin olmak için kapsamlı testler yapma ve performansı optimize etme.

Bu plan, link geçiş sayfalarında güçlü ve esnek bir reklam gösterim sistemi kurmak için gerekli adımları içermektedir.