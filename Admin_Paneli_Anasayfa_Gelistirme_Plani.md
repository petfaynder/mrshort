# Admin Paneli Anasayfası Geliştirme Planı

## I. Temel Sorun ve Hedef

*   **Mevcut Durum Özeti:**
    *   `users.earnings`: Kullanıcının toplam kazancını (muhtemelen link + referans) tek bir alanda tutuyor.
    *   `users.referred_by_user_id`: Referans sisteminin temelini oluşturuyor.
    *   `link_clicks`: Tıklama başına kazanç saklamıyor.
    *   `ReferralManager.php`: Referans kazancını, davet edilenlerin **toplam `earnings`**'i üzerinden anlık hesaplıyor ve kalıcı olarak saklamıyor. Bu, komisyon hesaplamasında ve metriklerin ayrıştırılmasında sorunlara yol açabilir.
*   **Hedef:** Admin panelinde "Toplam Yayıncı Kazancı", "Yayıncıların Linklerden Toplam Kazancı", "Yayıncıların Referanslardan Toplam Kazancı" ve "Toplam Görüntülenme" metriklerini; ayrıca bu kazançların günlük dökümünü doğru, ayrı ve performanslı bir şekilde göstermek.

## II. Çözüm: Veri Modeli ve İş Mantığı İyileştirmeleri

### A. Veritabanı Değişiklikleri (Yeni Bir Migrasyon Dosyası Oluşturulacak)

1.  **`users` Tablosuna Yeni Sütunlar Eklenecek:**
    *   `link_earnings` (DECIMAL, 10, 2, DEFAULT 0.00): Kullanıcının sadece link tıklamalarından elde ettiği kümülatif kazancı saklamak için.
    *   `referral_earnings` (DECIMAL, 10, 2, DEFAULT 0.00): Kullanıcının sadece referans programından elde ettiği kümülatif kazancı saklamak için.
2.  **Mevcut `users.earnings` Sütununun Geleceği:**
    *   Bu sütun, `link_earnings + referral_earnings` toplamını yansıtacak şekilde korunacak ve model seviyesinde (örneğin bir observer/event listener ile veya `save()` metodunda) otomatik olarak güncel tutulacaktır.

### B. İş Mantığı Değişiklikleri

1.  **Link Kazancı İşleme:**
    *   Bir link tıklandığında ve kullanıcıya bir kazanç yazılacağında:
        *   Bu kazanç miktarı, ilgili kullanıcının `link_earnings` sütununa eklenmeli.
        *   Aynı zamanda `earnings` sütunu da güncellenmeli (`link_earnings + referral_earnings`).
2.  **Referans Kazancı İşleme:**
    *   **Komisyon Tabanı Belirleme:** Davet eden kullanıcının alacağı komisyon, davet edilen kullanıcının **sadece `link_earnings`** üzerinden hesaplanmalıdır.
    *   **Komisyon Hesaplama ve Kaydetme Zamanı:** Bir davet edilen kullanıcı bir `link_earnings` elde ettiğinde, bu kazancın belirli bir yüzdesi (örneğin %15) anında davet eden kullanıcının `referral_earnings` sütununa eklenmeli.
    *   Davet edenin `earnings` sütunu da güncellenmeli (`link_earnings + referral_earnings`).
    *   **`ReferralManager.php` Güncellemesi:**
        *   `$this->referralEarnings` değişkeni artık doğrudan `Auth::user()->referral_earnings` üzerinden okunmalı.
        *   `getReferralEarningForUser(User $referredUser)` fonksiyonu, davet edilen kullanıcının `link_earnings` üzerinden komisyon hesaplamalı ve bu bilgi sadece gösterim amaçlı olmalı.

### C. Günlük Kazanç Dökümü İçin İleriye Yönelik Öneri (Bu Aşamanın Dışında Tutulabilir Ama Bilinmeli)

*   Admin panelindeki detaylı tabloda "Günlük Link Kazancı" ve "Günlük Referans Kazancı"nı en doğru şekilde göstermek için, her kazanç işlemini kaydeden ayrı bir `earnings_transactions` tablosu oluşturmak idealdir.
*   Bu ilk aşamada, günlük döküm için `users` tablosundaki kümülatif toplamların günlük değişimlerine bakılacak veya anlık hesaplamalar yapılacaktır.

## III. Admin Paneli Anasayfası Metriklerinin Hesaplanması (Önerilen Yeni Veri Modeliyle)

### A. Özet Bilgi Kartları

*   **"Total Publisher Earnings" (Toplam Yayıncı Kazancı):**
    *   Hesaplama: `User::sum('earnings')` (veya `User::sum('link_earnings') + User::sum('referral_earnings')`)
*   **"Link Earnings" (Yayıncıların Linklerden Toplam Kazancı):**
    *   Hesaplama: `User::sum('link_earnings')`
*   **"Referral Earnings" (Yayıncıların Referanslardan Toplam Kazancı):**
    *   Hesaplama: `User::sum('referral_earnings')`
*   **"Total Views" (Toplam Görüntülenme):**
    *   Hesaplama: `LinkClick::count()`

### B. İstatistik Grafiği (Günlük Toplam Tıklanma)

*   Veri Kaynağı: `LinkClick::selectRaw('DATE(created_at) as date, COUNT(*) as views')`
    `->groupBy('date')`
    `->orderBy('date', 'asc')`
    `->get()` (Seçilen tarih aralığına göre filtrelenmiş)

### C. Detaylı Veri Tablosu (Günlük Döküm - Kümülatif Yaklaşım)

*   **`Date` (Tarih)**
*   **`Views` (Görüntülenme):** O günkü `LinkClick::whereDate('created_at', $date)->count()`.
*   **`Link Earnings` (O Gün Sonu Toplam Link Kazancı):** O gün sonunda tüm kullanıcıların `SUM(link_earnings)` değeri.
*   **`Referral Earnings` (O Gün Sonu Toplam Referans Kazancı):** O gün sonunda tüm kullanıcıların `SUM(referral_earnings)` değeri.
*   **`Total Publisher Earnings` (O Gün Sonu Toplam Yayıncı Kazancı):** Yukarıdaki iki sütunun toplamı.
*   **`Daily CPM`:** `(O Gün Sonu Total Publisher Earnings / O Günkü Views) * 1000`.

## IV. Mermaid Diyagramı (Önerilen Kazanç ve Veri Akışı)

```mermaid
graph TD
    subgraph "Veritabanı (users tablosu)"
        U_LE[link_earnings]
        U_RE[referral_earnings]
        U_E[earnings (U_LE + U_RE)]
    end

    A[Link Tıklaması] --> B{Link Kazancı Hesapla};
    B --> U_LE;
    U_LE --> U_E;

    C[Davet Edilen Kullanıcı Link Kazancı Elde Eder (Kendi U_LE'si artar)] --> D{Referans Komisyonu Hesapla (Davet Edilenin U_LE'si üzerinden)};
    D --> G[Davet Eden Kullanıcının U_RE'si Artar];
    G --> U_E;


    subgraph "Admin Paneli Metrikleri"
        Card_TotalPubEarnings["Toplam Yayıncı Kazancı (SUM U_E)"]
        Card_LinkEarnings["Link Kazançları (SUM U_LE)"]
        Card_ReferralEarnings["Referans Kazançları (SUM U_RE)"]
        Card_TotalViews["Toplam Görüntülenme (COUNT link_clicks)"]

        Table_DailyViews["Günlük Görüntülenme"]
        Table_DailyLinkEarnings["Günlük Link Kazancı (İyileştirme Gerekli)"]
        Table_DailyReferralEarnings["Günlük Referans Kazancı (İyileştirme Gerekli)"]
        Table_DailyTotalPubEarnings["Günlük Toplam Yayıncı Kazancı"]
        Table_DailyCPM["Günlük CPM"]
    end

    U_E --> Card_TotalPubEarnings;
    U_LE --> Card_LinkEarnings;
    U_RE --> Card_ReferralEarnings;
    LC[link_clicks] --> Card_TotalViews;
    LC --> Table_DailyViews;

    U_LE --> Table_DailyLinkEarnings;
    U_RE --> Table_DailyReferralEarnings;
    Table_DailyLinkEarnings --> Table_DailyTotalPubEarnings;
    Table_DailyReferralEarnings --> Table_DailyTotalPubEarnings;
    Table_DailyTotalPubEarnings --> Table_DailyCPM;
    Table_DailyViews --> Table_DailyCPM;
```

## V. Eklenecek Ek Metrikler ve Bilgiler (Anasayfa)

Bu ek metrikler, admin paneli anasayfasında **ayrı bilgi kartları** şeklinde gösterilecektir.

### A. Kullanıcı Aktivitesi Metrikleri

*   **Yeni Kullanıcı Kayıtları (Son 24 saat, Son 7 gün, Son 30 gün):**
    *   Veri Kaynağı: `users` tablosu, `created_at` sütunu.
    *   Admin İçin Değeri: Yeni kullanıcı kazanım hızını ve trendlerini gösterir.
*   **Aktif Kullanıcı Sayısı (Son 24 saat / Son 7 gün / Son 30 gün):**
    *   Veri Kaynağı: `users` tablosundaki `last_login_at` veya `last_activity_at` gibi bir sütun (eğer yoksa, `link_clicks` veya diğer etkileşim tablolarından türetilebilir - örneğin belirli bir periyotta en az bir link tıklaması yapan veya link oluşturan kullanıcılar).
    *   Admin İçin Değeri: Platformun genel canlılığını ve kullanıcı bağlılığını gösterir.
*   **En Aktif Kullanıcılar (Belirli bir periyotta en çok link oluşturan/tıklama alan ilk 5-10 kullanıcı):**
    *   Veri Kaynağı: `links` tablosu (`user_id` ile gruplama ve `created_at`'e göre sayım), `link_clicks` tablosu (dolaylı olarak `links.user_id` üzerinden `created_at`'e göre gruplama ve sayım).
    *   Admin İçin Değeri: "Power user"ları ve potansiyel VIP kullanıcıları belirlemeye yardımcı olur, bu kullanıcılara özel teşvikler sunulabilir.

### B. Link Aktivitesi Metrikleri

*   **Oluşturulan Yeni Link Sayısı (Son 24 saat, Son 7 gün, Son 30 gün):**
    *   Veri Kaynağı: `links` tablosu, `created_at` sütunu.
    *   Admin İçin Değeri: Platformdaki içerik üretim hızını gösterir.
*   **Toplam Aktif Link Sayısı:**
    *   Veri Kaynağı: `links` tablosu (durumlarına göre sayım, örneğin `status = 'active'` veya `is_hidden = false`).
    *   Admin İçin Değeri: Sistemde aktif olarak kullanılan link miktarını gösterir.
*   **En Çok Tıklanan Linkler (İlk 5-10, tıklama sayısı ile, son 7/30 gün):**
    *   Veri Kaynağı: `links` tablosu ve `link_clicks` tablosu (join ile `links.id` üzerinden gruplama, `link_clicks.created_at`'e göre filtreleme ve sayım).
    *   Admin İçin Değeri: Popüler içerikleri, viral potansiyeli olan linkleri ve trendleri gösterir.
*   **Ortalama Tıklama Başına Kazanç (Genel CPM/CPC varyasyonu, son 7/30 gün):**
    *   Veri Kaynağı: `User::where('created_at', '>=', $startDate)->sum('link_earnings') / LinkClick::where('created_at', '>=', $startDate)->count()`.
    *   Admin İçin Değeri: Linklerin genel para kazanma etkinliğini ve bu etkinliğin zaman içindeki değişimini gösterir.
*   **Kullanılmayan/Popüler Olmayan Linkler (Belirli bir süredir - örn. son 90 gün - hiç tıklanmayan veya çok az tıklanan link sayısı):**
    *   Veri Kaynağı: `links` tablosu ve `link_clicks` tablosu (sol join ile belirli bir tarihten sonra `link_clicks` kaydı olmayan `links` kayıtlarını bulma).
    *   Admin İçin Değeri: Sistemdeki "ölü" linkleri, güncelliğini yitirmiş içerikleri ve potansiyel veritabanı temizlik alanlarını gösterir.

### C. Operasyonel Metrikler

*   **Bekleyen Para Çekme Talepleri (Sayı, Toplam Tutar):**
    *   Veri Kaynağı: `withdrawal_requests` tablosu, `status = 'pending'` (veya benzeri) ve `amount` sütunu.
    *   Admin İçin Değeri: Finansal yükümlülükleri ve işlem yapılması gereken talepleri gösterir.
*   **Açık Destek Talepleri (Sayı, kategorilere göre döküm - eğer mümkünse):**
    *   Veri Kaynağı: `tickets` tablosu, `status = 'open'` (veya benzeri), `category` sütunu.
    *   Admin İçin Değeri: Müşteri destek yükünü ve potansiyel sorun alanlarını gösterir.
*   **Sistem Sağlığı/Performans Göstergeleri (Örn: Ortalama Sayfa Yüklenme Süresi, API Yanıt Süreleri - eğer izleniyorsa):**
    *   Veri Kaynağı: APM (Application Performance Monitoring) araçları, sunucu logları veya Laravel Telescope gibi araçlar. (Bu, mevcut yapıya entegrasyon gerektirebilir.)
    *   Admin İçin Değeri: Teknik sorunları erken tespit etmeye, kullanıcı deneyimini iyileştirmeye ve altyapı darboğazlarını belirlemeye yardımcı olur.
*   **Veritabanı Boyutu / Büyüme Hızı (Ana tablolar için - örn: users, links, link_clicks):**
    *   Veri Kaynağı: Veritabanı yönetim araçları veya periyodik olarak çalıştırılacak SQL sorguları (`information_schema` vb.).
    *   Admin İçin Değeri: Altyapı planlaması, depolama yönetimi ve performans optimizasyonu için kritik bilgiler sunar.

### D. Hızlı Bakış Bilgileri

*   **En Çok Tıklama Alan Ülkeler (İlk 3-5, tıklama sayısı/yüzdesi, son 7/30 gün):**
    *   Veri Kaynağı: `link_clicks` tablosu, `country` sütunu (`created_at`'e göre filtreleme, gruplama ve sayım ile).
    *   Admin İçin Değeri: Trafiğin coğrafi dağılımını ve hedef kitle hakkında bilgi verir.
*   **Son Duyurular (Başlıklar ve yayınlanma tarihi - ilk 3-5):**
    *   Veri Kaynağı: `announcements` tablosu, `created_at` sütununa göre en son kayıtlar.
    *   Admin İçin Değeri: Kullanıcılara yapılan son bilgilendirmeleri hızlıca görmeyi sağlar.
*   **En Çok Kazanan Kullanıcılar (İlk 5-10, toplam kazançları ile, son 7/30 gün veya genel):**
    *   Veri Kaynağı: `users` tablosu, `earnings` (veya `link_earnings` + `referral_earnings`) sütununa göre sıralama. Eğer periyodik isteniyorsa, `earnings_transactions` gibi bir tabloya ihtiyaç duyulabilir veya anlık hesaplama yapılabilir.
    *   Admin İçin Değeri: Platforma en çok finansal katkı sağlayan yayıncıları ve potansiyel iş ortaklarını gösterir.
*   **En Çok Referans Getiren Kullanıcılar (İlk 5-10, getirdikleri aktif referans sayısı veya son 30 gündeki referans kazancı ile):**
    *   Veri Kaynağı: `users` tablosu (`referred_by_user_id` ile gruplama ve sayım) veya `users.referral_earnings` sütunu (eğer periyodik kazanç isteniyorsa `earnings_transactions` benzeri bir yapı).
    *   Admin İçin Değeri: Referans programının etkinliğini, en başarılı "elçileri" ve network etkisini gösterir.
*   **Son Kayıt Olan Kullanıcılar (İlk 5-10, kullanıcı adı, e-posta ve kayıt tarihi):**
    *   Veri Kaynağı: `users` tablosu, `created_at` sütununa göre en son kayıtlar.
    *   Admin İçin Değeri: Yeni kullanıcı akışını anlık takip etmeyi, hoş geldin süreçlerini ve potansiyel sahte kayıtları izlemeyi sağlar.