# Admin Paneli "Links" Sayfası Geliştirme Planı

Bu plan, admin panelindeki "Links" sayfasının mevcut eksikliklerini gidermek ve yeni özellikler eklemek için atılacak adımları içermektedir.

## Mevcut Durum

*   Admin paneli "Links" sayfasına erişilebiliyor.
*   Linkler listede gözükmüyor.
*   Edit butonuna basıldığında boş bir ekran geliyor.
*   Gelişmiş filtreleme ve arama özellikleri eksik.
*   "Hidden Links" ve "Inactive Links" sayfaları eksik.

## Hedefler

*   "Links" sayfasında linklerin doğru şekilde listelenmesini sağlamak.
*   Link düzenleme sayfasında gerekli form alanlarını görüntülemek ve düzenlemeye izin vermek.
*   "Links" sayfasına gelişmiş filtreleme ve arama özellikleri eklemek.
*   "Hidden Links" ve "Inactive Links" adında yeni sayfalar oluşturmak ve bu sayfalarda ilgili linkleri listelemek.

## Plan Adımları

1.  **Links Sayfası İçeriği ve Edit Sayfası Geliştirme:**
    *   `app/Filament/Resources/LinkResource.php` dosyasını güncelleyerek `table()` metodundaki sütun tanımlarının doğruluğunu kontrol etme ve gerekli düzenlemeleri yapma.
    *   `app/Filament/Resources/LinkResource.php` dosyasındaki `form()` metoduna link düzenleme için gerekli form alanlarını ekleme:
        *   Status (Active/Inactive - `is_hidden` alanı kullanılabilir veya yeni bir `status` alanı eklenebilir)
        *   Long URL (`original_url`)
        *   Title (`title`)
        *   Description (Eğer modelde varsa veya eklenecekse)
        *   Expiration date (`expires_at`)
        *   Advertising Type (Eğer modelde varsa veya eklenecekse)

2.  **Gelişmiş Filtreleme ve Arama Ekleme:**
    *   `app/Filament/Resources/LinkResource.php` dosyasındaki `filters()` metoduna aşağıdaki filtreleri ekleme:
        *   Link ID (`id`)
        *   User (Kullanıcı ilişkisi üzerinden `user_id`)
        *   Alias (`code`)
        *   Advertising Type (Eğer modelde varsa veya eklenecekse)
        *   Title, Description veya URL (`title`, `description`, `original_url` alanlarında arama yapacak bir TextInput filtresi)

3.  **"Hidden Links" ve "Inactive Links" Sayfaları Oluşturma:**
    *   `app/Filament/Resources/LinkResource/Pages` dizini altına `ListHiddenLinks.php` ve `ListInactiveLinks.php` adında yeni Filament sayfa sınıfları oluşturma.
    *   `ListHiddenLinks.php` sayfasında, LinkResource tablosunu kullanarak `is_hidden` alanı true olan linkleri filtreleme.
    *   "Inactive Links" için modelde bir `is_active` veya `status` alanı yoksa, bu alanı veritabanına eklemek için yeni bir migration oluşturma. Eğer `expires_at` alanı "inactive" durumu için yeterliyse, bu alana göre filtreleme yapma.
    *   `ListInactiveLinks.php` sayfasında, LinkResource tablosunu kullanarak "inactive" durumdaki linkleri filtreleme.
    *   `app/Filament/Resources/LinkResource.php` dosyasındaki `getPages()` metoduna bu yeni sayfaları ekleme.

## Tahmini Süre

Bu planın uygulanması, mevcut kod tabanının karmaşıklığına ve "Inactive Links" için yeni bir alan gerekip gerekmediğine bağlı olarak değişebilir. Tahmini süre X saattir. (Bu kısım kullanıcı tarafından doldurulabilir veya tartışılabilir.)

## Onay

Bu planı onaylıyor musunuz? Onayınız durumunda, planı uygulamak için "Code" moduna geçiş yapabiliriz.