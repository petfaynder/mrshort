# Raporlama ve Admin Paneli Geliştirme Planı

Bu belge, link kısaltma servisindeki raporlama özelliklerinin geliştirilmesi ve admin paneline ek özelliklerin kazandırılması için bir plan sunmaktadır.

## Mevcut Durum

*   Link tıklama verileri (cihaz türü, işletim sistemi, tarayıcı, yönlendiren) artık veritabanına doğru kaydediliyor ve kullanıcı reports sayfasında görünüyor.
*   Ülke bilgisi hala "Bilinmiyor" olarak görünüyor.
*   Admin paneli için kullanıcı bazlı raporlama ve para çekme talebi detayları mevcut değil.
*   Organik trafik tespiti için ek veri toplama mekanizması mevcut değil.

## Hedefler

*   Reports sayfasında ülke bilgisinin doğru şekilde gösterilmesini sağlamak.
*   Admin paneline kullanıcı bazlı detaylı tıklama raporlama özelliği eklemek.
*   Admin paneline para çekme taleplerine esas olan tıklama verilerini görüntüleme özelliği eklemek.
*   Trafiğin organik olup olmadığını anlamak için ek veri toplama ve admin panelinde gösterme mekanizması geliştirmek.

## Plan Adımları

Aşağıdaki adımlar, yukarıda belirtilen hedeflere ulaşmak için izlenecek yolu detaylandırmaktadır:

### Adım 1: Ülke Bilgisini Tespit Etme Sorununu Çözme

*   `app/Http/Controllers/LinkController.php` dosyasındaki GeoIP entegrasyonunu detaylı olarak inceleyin.
*   GeoIP veritabanı dosyasının (`storage/app/geoip/GeoLite2-Country.mmdb`) doğru konumda olup olmadığını ve uygulamanın bu dosyaya erişim izni olup olmadığını kontrol edin.
*   GeoIP kütüphanesinin doğru çalıştığını doğrulamak için `LinkController`'daki ilgili kısma ek debug logları ekleyin. IP adresinden ülke bilgisi alınıp alınamadığını kontrol edin.
*   Eğer GeoIP doğru çalışmıyorsa, GeoIP veritabanını güncelleyin veya kurulumunu/yapılandırmasını düzeltin.
*   Alternatif olarak, IP adresinden konum bilgisi almak için farklı bir servis veya kütüphane kullanmayı değerlendirin ve entegrasyonunu yapın.

### Adım 2: Admin Paneli Kullanıcı Bazlı Raporlama Özelliği Ekleme

*   Admin paneli yapısını ve kullanıcı yönetimini sağlayan dosyaları (`app/Filament/Resources/UserResource.php` gibi) inceleyin.
*   Admin panelinde kullanıcıları listelemek ve belirli bir kullanıcıyı seçmek için bir arayüz (örneğin, kullanıcı listeleme sayfasında bir "Raporları Görüntüle" butonu veya kullanıcı detay sayfasında bir bölüm) oluşturun.
*   Seçilen kullanıcıya ait tıklama verilerini çekmek için `app/Livewire/User/ReportsManager.php` Livewire bileşenindeki veri çekme mantığını (özellikle `getBaseQuery` ve diğer `getClicksBy...` metotları) admin paneli bağlamında yeniden kullanın veya admin paneli için ayrı bir raporlama mantığı geliştirin.
*   Admin panelinde reports sayfasındaki gibi tabloları ve grafikleri (seçilen kullanıcıya özel verilerle) görüntülemek için yeni Blade şablonları veya Filament sayfaları oluşturun.
*   Admin kullanıcılarının bu raporlara erişebilmesi için gerekli yetkilendirmeleri (Policies) tanımlayın.

### Adım 3: Admin Paneli Para Çekme Talebi Tıklama Detayları Özelliği Ekleme

*   Para çekme (Withdrawal) taleplerinin veritabanı yapısını (`database/migrations/..._create_withdrawals_table.php`) ve admin panelindeki yönetimini sağlayan dosyaları (`app/Filament/Resources/WithdrawalResource.php` gibi) inceleyin.
*   `link_clicks` tablosuna, hangi tıklamaların hangi para çekme talebiyle ilişkili olduğunu belirten yeni bir sütun (`withdrawal_id` gibi, foreign key olarak `withdrawals` tablosuna bağlı) eklemek için yeni bir migration oluşturun ve çalıştırın.
*   Kullanıcı para çekme talebi gönderdiğinde, o talebe esas olan tıklamaları belirleyin ve bu tıklamaların `withdrawal_id` alanını ilgili para çekme talebinin ID'si ile güncelleyin. Bu mantık, para çekme talebi oluşturma sürecinde (`app/Http/Controllers/WithdrawalController.php` gibi bir yerde) veya ayrı bir servis/işlem içinde uygulanabilir.
*   Admin panelinde para çekme talebi detay sayfasında, ilgili para çekme talebiyle ilişkili tıklama verilerini (`withdrawal_id` sütununu kullanarak) çekip görüntülemek için bir arayüz (tablo) oluşturun.

### Adım 4: Organik Trafik Tespiti İçin Ek Veri Toplama

*   Organik trafiği belirlemek için hangi ek verilerin faydalı olabileceğini araştırın. Örnekler:
    *   Tıklama hızı (aynı IP'den kısa sürede çok sayıda tıklama)
    *   Tekil/çoğul tıklama oranı anormallikleri
    *   Bilinen bot IP adresleri veya kullanıcı ajanları listeleri
    *   Tıklama süresi (kullanıcının hedef sayfada ne kadar kaldığı - bu daha karmaşık olabilir)
*   Seçilen ek verileri toplamak için `app/Http/Controllers/LinkController.php` dosyasındaki tıklama kaydetme mantığını güncelleyin. Bu veriler, request header'larından, IP adresinden veya ek kütüphaneler kullanılarak elde edilebilir.
*   Bu ek verileri saklamak için `link_clicks` tablosuna yeni sütunlar eklemek için migration oluşturun ve çalıştırın.
*   Admin panelinde para çekme talebi detay sayfasında veya kullanıcı raporları sayfasında bu ek verileri görüntülemek için arayüz oluşturun. Anormal durumları (potansiyel bot trafiği) vurgulayacak görselleştirmeler veya işaretler eklemeyi düşünün.

## Sonraki Adımlar

Plan onaylandıktan sonra, bu planı uygulamak için Code moduna geçiş yapabiliriz. Her adım kendi içinde daha küçük görevlere bölünecektir.