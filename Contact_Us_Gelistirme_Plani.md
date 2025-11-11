# Contact Us Sayfası Geliştirme Planı

Bu plan, kullanıcı arayüzündeki Contact Us sayfasını geliştirmek, admin panelinde ticket yönetimini sağlamak ve yapay zeka desteği entegrasyonu için atılacak adımları detaylandırmaktadır.

## 1. Veritabanı Yapısı

*   **`tickets` Tablosu:** Mevcut `tickets` tablosuna aşağıdaki sütunlar eklenecektir:
    *   `category` (string): Ticket kategorisini belirtmek için (Örn: Ödeme, Teknik, Genel).
    *   `priority` (string): Ticket önceliğini belirtmek için (Örn: Düşük, Orta, Yüksek).
*   **`ticket_replies` Tablosu:** Ticket cevaplarını saklamak için yeni bir tablo oluşturulacaktır. Sütunları:
    *   `id` (primary key)
    *   `ticket_id` (foreign key to `tickets` table)
    *   `user_id` (foreign key to `users` table - cevabı yazan kullanıcı, admin veya kullanıcı olabilir)
    *   `message` (text)
    *   `created_at`, `updated_at` (timestamps)

## 2. Kullanıcı Arayüzü (Contact Us Sayfası)

*   **Dosyalar:** `resources/views/user/contact/index.blade.php` ve `app/Livewire/User/TicketManager.php` güncellenecektir.
*   **Ticket Oluşturma Formu:**
    *   Konu ve mesaj alanlarına ek olarak kategori ve öncelik seçimi için dropdown veya benzeri alanlar eklenecektir.
    *   (İsteğe bağlı) Dosya ekleme alanı eklenebilir.
*   **Mevcut Ticket Listeleme:**
    *   Kullanıcının oluşturduğu ticket'lar listelenecektir.
    *   Her ticket için konu, oluşturulma tarihi, durumu (Açık, Kapalı vb.) gösterilecektir.
    *   Kullanıcılar kendi ticket'larının durumunu (açık/kapalı) güncelleyebilecektir, ancak admin tarafından kapatılan ticket'ları açamayacaktır.
    *   Ticket detayına gitmek için bir link eklenecektir (eğer detay sayfası oluşturulacaksa).
*   **Ticket Detay Sayfası (İsteğe bağlı):**
    *   Eğer detay sayfası oluşturulursa, ticket'ın tüm mesajlaşma geçmişi (kullanıcı ve admin cevapları) gösterilecektir.
    *   Kullanıcı bu sayfadan ticket'a yeni mesaj yazabilecektir (ticket admin tarafından kapatılmamışsa).
*   **Yapay Zeka Entegrasyonu (Aşama 1):**
    *   Kullanıcı mesaj yazarken, yazdığı metne göre ilgili SSS maddeleri gösterilerek otomatik yönlendirme sağlanabilir.

## 3. Admin Paneli (Ticket Yönetimi)

*   **Filament Resource:** `app/Filament/Resources/TicketResource.php` dosyası oluşturulacaktır.
*   **Ticket Listeleme:**
    *   Tüm ticket'lar Filament tablosunda listelenecektir.
    *   Tabloda ticket'ın konusu, gönderen kullanıcı, durumu, kategorisi, önceliği, oluşturulma tarihi ve son güncelleme tarihi gibi sütunlar bulunacaktır.
    *   Durum, kategori ve önceliğe göre filtreleme ve arama özellikleri eklenecektir.
*   **Ticket Detay Görüntüleme ve Cevaplama:**
    *   Admin bir ticket'a tıkladığında, ticket'ın detay sayfasını görecektir.
    *   Bu sayfada ticket'ın tüm bilgileri ve mesajlaşma geçmişi görüntülenecektir.
    *   Adminin cevap yazabileceği bir form ve "Cevap Gönder" butonu olacaktır. Cevaplar `ticket_replies` tablosuna kaydedilecektir.
    *   Admin ticket'ın durumunu (Açık, Devam Ediyor, Çözüldü, Kapalı) güncelleyebilecektir.
    *   Admin tarafından "Kapalı" olarak işaretlenen ticket'lar kullanıcı tarafından tekrar açılamayacaktır.
*   **Yapay Zeka Entegrasyonu (Aşama 1):**
    *   Yeni ticket oluşturulduğunda otomatik kategorizasyon ve öncelik atama yapılabilir.

## 4. Yapay Zeka Entegrasyonu (Detaylandırma)

*   **Aşama 1: Temel Otomasyon ve Kullanıcı Yönlendirme:**
    *   SSS Tabanlı Otomatik Yanıtlama: Kullanıcı mesajına göre SSS önerileri sunulması.
    *   Otomatik Ticket Kategorizasyonu ve Önceliklendirme: Yeni ticket'lara otomatik kategori ve öncelik atanması.
*   **Aşama 2: Gelişmiş Destek ve Otomasyon (İsteğe bağlı):**
    *   Önerilen Cevaplar: Adminlere benzer ticket'lara verilen geçmiş cevapların önerilmesi.
    *   Duygu Analizi: Ticket mesajlarının duygu analizinin yapılması.
*   **Aşama 3: Tam Otomasyon ve Chatbot Entegrasyonu (İsteğe bağlı):**
    *   Tam Otomatik Cevaplama: Belirli ticket türlerine yapay zeka tarafından otomatik cevap verilmesi.

## Uygulama Adımları

1.  `ticket_replies` tablosu için migrasyon dosyası oluşturulması ve çalıştırılması.
2.  `tickets` tablosuna `category` ve `priority` sütunları için migrasyon dosyası oluşturulması ve çalıştırılması.
3.  `TicketReply` modelinin oluşturulması.
4.  `Ticket` ve `User` modellerine gerekli ilişkilerin (relationships) eklenmesi.
5.  `app/Livewire/User/TicketManager.php` ve `livewire/user/ticket-manager.blade.php` dosyalarının güncellenmesi (form alanları, durum yönetimi, mesajlaşma gösterimi).
6.  `app/Filament/Resources/TicketResource.php` dosyasının oluşturulması ve Filament kaynak tanımlarının yapılması (liste, görüntüleme, cevaplama, durum yönetimi).
7.  Yapay zeka entegrasyonu için backend fonksiyonlarının ve API çağrılarının geliştirilmesi (seçilen yapay zeka özelliklerine göre).
8.  Kullanıcı arayüzünde yapay zeka sonuçlarını gösterecek entegrasyonların yapılması.

Bu plan, Contact Us sayfasını kapsamlı bir destek sistemine dönüştürmek için bir yol haritası sunmaktadır.