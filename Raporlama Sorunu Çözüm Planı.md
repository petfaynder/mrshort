# Raporlama Sorunu Çözüm Planı

## Sorun Tespiti

Raporlar sayfasında ülke, cihaz, zaman, işletim sistemi, tarayıcı ve yönlendiren domain bilgilerinin "Bilinmiyor" olarak görünmesi.

## Mevcut Durum Analizi

-   `link_clicks` tablosunda ilgili sütunlar (country, device_type, os, browser, referrer) mevcut.
-   `LinkController`'da link tıklamaları sırasında GeoIP2 ve Jenssegers\Agent kütüphaneleri kullanılarak bu bilgilerin toplanmaya çalışıldığı görüldü.
-   GeoIP2 sadece ülke kodunu alıyor ve hata durumunda loglama yorum satırı yapılmış.
-   Jenssegers\Agent kütüphanesi cihaz, OS ve tarayıcı bilgilerini alıyor.
-   Referrer bilgisi HTTP başlığından alınıyor ve boş olabilir.
-   Raporlama mantığı `ReportsManager` Livewire bileşeninde bulunuyor ve ilgili sütunlara göre gruplama yaparak verileri çekiyor.

## Potansiyel Sorun Kaynakları

1.  **Veri Toplama Hatası:** `LinkController`'da veri toplama sırasında hatalar oluşuyor veya bazı durumlarda bilgiler doğru alınamıyor (GeoIP veritabanı hatası, User Agent ayrıştırma hatası, Referrer başlığının olmaması).
2.  **Veri Sunumu Hatası:** Raporlama kısmında `null` veya boş değerlerin "Bilinmiyor" olarak gösterilmesi (bu durum zaten ele alınıyor gibi görünüyor, sorun daha çok verinin kendisinin boş kaydedilmesi).

## Detaylı Plan

1.  **GeoIP Loglamayı Aktif Etme:**
    *   `app/Http/Controllers/LinkController.php` dosyasında GeoIP hata yakalama bloğundaki loglama satırını aktif hale getirmek. Bu, GeoIP ile ilgili hataları tespit etmemize yardımcı olacaktır.
2.  **Raporlarda "Bilinmiyor" Durumunu İyileştirme (Gerekirse):**
    *   `app/Livewire/User/ReportsManager.php` dosyasında, raporlama metotlarında `null` veya boş değerler için "Bilinmiyor" yerine daha açıklayıcı ifadeler kullanılabilir veya bu durumlar için ayrı bir kategori oluşturulabilir. (Mevcut durumda "Bilinmiyor" kullanılıyor, bu adım daha çok sunumun netleştirilmesi için).
3.  **Veri Toplama Hatalarını Giderme:**
    *   **GeoIP için:** `storage/app/geoip/GeoLite2-Country.mmdb` dosyasının varlığını ve bütünlüğünü kontrol etmek. Gerekirse dosyayı yeniden indirmek veya güncellemek.
    *   **Referrer için:** Referrer başlığı boş olduğunda `link_clicks` tablosuna "Doğrudan Erişim" gibi bir değer kaydetmek.
    *   **Agent için:** Nadir durumlar için "Unknown" yerine daha genel bir kategori kullanmak veya bu durumları raporlarda ayrı ele almak.
4.  **Test Etme:**
    *   Yapılan değişikliklerden sonra linklere farklı cihazlar, tarayıcılar ve yönlendirme kaynaklarından tıklayarak verilerin doğru kaydedildiğini ve raporlarda düzgün göründüğünü test etmek.

## İş Akışı Diyagramı

```mermaid
graph TD
    A[Kullanıcı Linke Tıklar] --> B{Link Bulundu mu?};
    B -- Evet --> C[Kullanıcı Bilgilerini Topla];
    C --> D[IP Adresi];
    C --> E[User Agent];
    C --> F[Referrer Başlığı];
    D --> G{GeoIP Sorgusu};
    G -- Başarılı --> H[Ülke Kodunu Al];
    G -- Başarısız --> I[Ülke = null veya Hata Logla];
    E --> J[Agent ile Cihaz, OS, Tarayıcı Bilgisi Al];
    F --> K[Referrer Bilgisini Al (Boşsa "Doğrudan Erişim" Ata)];
    H --> L[LinkClick Kaydını Oluştur];
    I --> L;
    J --> L;
    K --> L;
    L --> M[Kazanç Hesapla ve Kullanıcıya Ekle];
    M --> N[Reklam Sayfasına Yönlendir];
    B -- Hayır --> O[Hata Sayfasına Yönlendir];
    N --> P[Kullanıcı Orijinal Linke Yönlendirilir];

    Q[Raporlar Sayfası Açılır];
    Q --> R[ReportsManager Bileşeni Yüklenir];
    R --> S[Tarih Aralığına Göre LinkClick Verilerini Çek];
    S --> T[Ülkeye Göre Grupla (Null/Boş Değerleri Ele Al)];
    S --> U[Cihaza Göre Grupla (Null/Boş Değerleri Ele Al)];
    S --> V[OS'a Göre Grupla (Null/Boş Değerleri Ele Al)];
    S --> W[Tarayıcıya Göre Grupla (Null/Boş Değerleri Ele Al)];
    S --> X[Referrer'a Göre Grupla (Null/Boş Değerleri Ele Al)];
    T,U,V,W,X --> Y[Rapor Verilerini Hazırla];
    Y --> Z[Blade Görünümünde Göster];