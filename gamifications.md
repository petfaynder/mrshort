# Gamification Sistemi Geliştirme Planı

## Yeni Gamification Gereksinimleri ve Detaylı Plan

### 1. Rozet Tasarımları ve Sergileme Sayfası Geliştirme

#### 1.1. Rozet Tasarım Konseptleri ve Gereksinimleri:

*   **Görsel Çeşitlilik ve Tematik Yaklaşım:**
    *   Her rozet, temsil ettiği başarıyı yansıtan benzersiz bir ikon veya illüstrasyona sahip olmalı.
    *   **Örnekler:**
        *   **Link Kısaltma Başarımları:** Roket, zincir, makas, hedef tahtası gibi ikonlar.
        *   **Tıklama Başarımları:** Göz, büyüteç, trafik ışığı, popülerlik grafiği gibi ikonlar.
        *   **Sosyal Paylaşım Başarımları:** Megafon, paylaşım ikonu, kelebek, ağ gibi ikonlar.
        *   **Referans Başarımları:** El sıkışma, insan zinciri, hediye kutusu gibi ikonlar.
        *   **Gelir Başarımları:** Para torbası, altın sikke, dolar işareti, büyüme grafiği gibi ikonlar.
        *   **Seviye Başarımları:** Kalkan, taç, yıldız, rütbe işaretleri gibi ikonlar.
*   **Seviyelendirilmiş Rozetler:**
    *   Bazı başarımlar için (örn: link kısaltma sayısı, tıklama sayısı) Bronz, Gümüş, Altın, Platin, Elmas gibi farklı seviyelerde rozetler olmalı.
    *   Her seviye için rozetin rengi, parlaklığı veya üzerindeki detaylar değişmeli (örn: Bronz rozet mat, Altın rozet parlak ve detaylı).
*   **Durum Görselleştirmesi:**
    *   **Kazanılmamış Rozetler:** Sönük, gri tonlarda veya kilitli bir ikonla gösterilmeli.
    *   **Kazanılmış Rozetler:** Canlı renklerde, parlak ve belki hafif bir animasyonla gösterilmeli.
    *   **İlerleme Durumu:** Rozetin üzerinde veya altında küçük bir ilerleme çubuğu veya sayısal ifade ile gösterilmeli (örn: 7/10).
*   **Boyut ve Çözünürlük:**
    *   Rozet görselleri farklı ekran boyutlarına uyum sağlayacak şekilde yüksek çözünürlüklü ve ölçeklenebilir olmalı (SVG veya yüksek kaliteli PNG önerilir).
    *   Küçük boyutlarda bile detayların anlaşılır olması sağlanmalı.
*   **Dosya Formatı:** PNG (şeffaf arka plan için) veya SVG (ölçeklenebilirlik için) tercih edilmeli.

#### 1.2. `Achievements` Sayfası Tasarımı (Kullanıcı Paneli) İçin Öneriler:

*   **Kategoriye Göre Gruplama:** Hedefleri "Günlük Görevler", "Haftalık Görevler", "Kariyer Başarımları", "Sosyal Başarımlar" gibi sekmeler veya filtreler aracılığıyla gruplandırma.
*   **Görsel Izgara Düzeni:** Rozetleri büyük, görsel odaklı bir ızgara düzeninde sergileme. Her kart bir rozeti temsil etmeli.
*   **İlerleme Görselleştirmesi:** Her başarım kartında ilerleme çubuğunu daha belirgin hale getirme (örn: renkli dolgu, yüzde gösterimi).
*   **Detaylı Bilgi Modalı:** Rozete tıklandığında açılan bir modal ile başarımın detaylı açıklaması, kazanılan ödüller, tamamlanma tarihi ve sosyal medya paylaşım seçenekleri.
*   **Filtreleme/Sıralama:** Kullanıcının tamamlanmış/tamamlanmamış başarımları filtrelemesi veya zorluk/kategoriye göre sıralaması için arayüz öğeleri.

### 2. Kapsamlı Hedefler (Goals) ve Başarımlar (Achievements) Oluşturma

#### 2.1. Hedef Kategorileri ve Zorluk Seviyeleri Genişletme:

*   **Kategoriler:**
    *   **Günlük Görevler:** Her gün sıfırlanan küçük hedefler.
    *   **Haftalık Görevler:** Her hafta sıfırlanan orta düzey hedefler.
    *   **Kariyer Başarımları:** Tek seferlik, uzun vadeli ve zorlu hedefler.
    *   **Sosyal Başarımlar:** Paylaşım ve referans odaklı hedefler.
    *   **Etkinlik Bazlı Başarımlar:** Özel etkinlikler veya kampanyalar sırasında aktif olan hedefler.
    *   **Ekonomik Başarımlar:** Belirli bir gelir eşiğine ulaşma veya sanal para harcama/kazanma hedefleri.
    *   **Keşif Başarımları:** Uygulamanın farklı özelliklerini kullanmaya teşvik eden hedefler (örn: ilk reklam kampanyasını oluştur, ilk destek talebini gönder).
*   **Zorluk Seviyeleri:** Kolay, Orta, Zor, Uzman, Efsanevi.

#### 2.2. Yüzlerce Yeni Hedef Fikri (Örnekler):

*   **Link Kısaltma Hedefleri:**
    *   **Kolay:** İlk linkini kısalt (1), 5 link kısalt, 10 link kısalt.
    *   **Orta:** 50 link kısalt, 100 link kısalt, bir günde 10 link kısalt.
    *   **Zor:** 500 link kısalt, 1000 link kısalt, bir haftada 50 link kısalt.
    *   **Uzman:** 5000 link kısalt, 10.000 link kısalt, belirli bir kategoride 100 link kısalt.
    *   **Efsanevi:** 50.000 link kısalt, 100.000 link kısalt.
*   **Tıklama Alma Hedefleri:**
    *   **Kolay:** İlk 10 tıklama al, 50 tıklama al, 100 tıklama al.
    *   **Orta:** 500 tıklama al, 1000 tıklama al, bir linkten 100 tıklama al.
    *   **Zor:** 5000 tıklama al, 10.000 tıklama al, bir günde 500 tıklama al.
    *   **Uzman:** 50.000 tıklama al, 100.000 tıklama al, belirli bir ülkeden 1000 tıklama al.
    *   **Efsanevi:** 1.000.000 tıklama al.
*   **Sosyal Paylaşım Hedefleri:**
    *   **Kolay:** İlk linkini paylaş, 5 link paylaş, 10 link paylaş.
    *   **Orta:** 50 link paylaş, farklı sosyal medya platformlarında 10 link paylaş.
    *   **Zor:** 100 link paylaş, bir günde 5 link paylaş.
    *   **Uzman:** 500 link paylaş, 1000 link paylaş.
*   **Referans Getirme Hedefleri:**
    *   **Kolay:** İlk referansını getir, 3 referans getir.
    *   **Orta:** 5 referans getir, referansların toplam 100 tıklama almasını sağla.
    *   **Zor:** 10 referans getir, referansların toplam 1000 tıklama almasını sağla.
    *   **Uzman:** 25 referans getir, referansların toplam 10.000 tıklama almasını sağla.
*   **Gelir Elde Etme Hedefleri:**
    *   **Kolay:** İlk $1 kazan, $5 kazan.
    *   **Orta:** $10 kazan, bir günde $1 kazan.
    *   **Zor:** $50 kazan, bir haftada $5 kazan.
    *   **Uzman:** $100 kazan, bir ayda $50 kazan.
    *   **Efsanevi:** $1000 kazan, $5000 kazan.
*   **Site İçi Etkileşim Hedefleri:**
    *   **Kolay:** Profilini tamamla, ayarlarını güncelle, ilk destek talebini gönder.
    *   **Orta:** 3 farklı aracı kullan (Toplu Kısaltıcı, Tam Sayfa Script vb.), 5 gün üst üste giriş yap.
    *   **Zor:** Liderlik tablosunda ilk 10'a gir, bir hafta boyunca günlük görevleri tamamla.
    *   **Uzman:** Bir reklam kampanyası oluştur, 5 farklı ülkeye link paylaş.

### 3. Gelişmiş Ödül Sistemi (Rewards) Taslağı

#### 3.1. Mevcut Ödül Türlerini Çeşitlendirme:

*   **Rozetler (Başarımlar):** Daha önce bahsedilen seviyelendirilmiş ve tematik rozetler.
*   **Puanlar:** Seviye atlamak ve mağazada harcamak için kullanılan temel birim.
*   **Sanal Para Birimi (Coinler/Jetonlar):** Mağazada özel öğeler satın almak için kullanılan premium para birimi.

#### 3.2. Yeni Ödül Türleri:

*   **Özel Avatarlar/Profil Temaları:** Kullanıcıların profillerini kişiselleştirmeleri için benzersiz görseller ve temalar.
*   **Reklam Gösterim Önceliği:** Belirli bir süre boyunca kullanıcının linklerindeki reklamların daha sık gösterilmesi (örn: 1 hafta boyunca %10 daha fazla gösterim).
*   **Premium Özelliklere Geçici Erişim:** Belirli bir süre boyunca premium özelliklere (örn: reklamsız linkler, gelişmiş istatistikler, özel raporlar) erişim (örn: 3 günlük Premium erişim).
*   **İndirim Kuponları:** Site içi hizmetlerde (örn: reklam kampanyası oluşturma, premium üyelik) indirim sağlayan kuponlar (örn: %25 indirim kuponu).
*   **Gerçek Dünya Ödülleri (Opsiyonel):** Çok yüksek seviyeler veya özel etkinlikler için fiziksel ödüller (örn: tişört, kupa, hediye çeki).
*   **Deneyim Puanı Artırıcılar:** Belirli bir süre boyunca kazanılan deneyim puanlarını artıran öğeler (örn: 24 saat boyunca %50 daha fazla XP).

#### 3.3. Puanları Site İçinde Başka Şeylere Dönüştürme ("Mağaza" veya "Takas Sistemi"):

*   **Mağaza (Shop):** Kullanıcıların biriktirdikleri puanlar veya sanal para birimi ile yeni avatarlar, profil temaları, indirim kuponları, reklam gösterim önceliği, deneyim puanı artırıcılar gibi dijital veya sanal ürünleri satın alabileceği bir bölüm.
    *   **Fiyatlandırma:** Her ürün için puan ve/veya sanal para birimi cinsinden fiyatlandırma.
    *   **Kategoriler:** Mağaza içinde "Avatarlar", "Temalar", "Güçlendirmeler", "Kuponlar" gibi kategoriler.
*   **Takas Sistemi:** Belirli miktarda puanın sanal paraya veya tam tersine dönüştürülebileceği bir sistem (örn: 1000 puan = 100 coin).
*   **Envanter (Inventory):** Kazanılan veya satın alınan tüm ödüllerin (rozetler, avatarlar, temalar, kuponlar, güçlendirmeler) görüntülendiği ve yönetildiği bir sayfa. Kullanıcı burada ödüllerini etkinleştirebilir veya kullanabilir.

### 4. Teknik Gereksinimler ve Entegrasyon Planı

*   **Veritabanı Şeması Güncellemeleri:**
    *   `gamification_goals` tablosuna `goal_type_config` (JSON) alanı: Hedef türüne özgü ek konfigürasyonları saklamak için (örn: "belirli kategori link kısaltma" hedefi için kategori ID'si).
    *   `gamification_rewards` tablosuna `reward_config` (JSON) alanı: Ödül türüne özgü ek konfigürasyonları saklamak için (örn: "indirim kuponu" için indirim yüzdesi, "premium erişim" için süre).
    *   `user_inventory` tablosuna `is_active` (boolean) ve `expires_at` (datetime) alanları: Kullanıcının envanterindeki öğelerin aktiflik durumunu ve son kullanma tarihini yönetmek için.
*   **Olay Dinleyicileri (Event Listeners):**
    *   `LinkCreatedEvent`: Yeni link kısaltma hedeflerini tetikler.
    *   `LinkClickedEvent`: Link tıklama hedeflerini tetikler.
    *   `LinkSharedEvent`: Link paylaşım hedeflerini tetikler.
    *   `ReferralRegisteredEvent`: Referans hedeflerini tetikler.
    *   `EarningAchievedEvent`: Gelir elde etme hedeflerini tetikler.
    *   `ProfileUpdatedEvent`: Profil tamamlama hedeflerini tetikler.
    *   `SupportTicketCreatedEvent`: Destek talebi hedeflerini tetikler.
    *   `AdCampaignCreatedEvent`: Reklam kampanyası oluşturma hedeflerini tetikler.
    *   **Genel Hedef Kontrol Mekanizması:** Her olay tetiklendiğinde, ilgili tüm aktif hedefleri kontrol eden ve kullanıcının ilerlemesini güncelleyen merkezi bir servis/işlem.
*   **Livewire Bileşenleri ve Görünümler:**
    *   `Achievements` sayfasını yeni tasarıma göre (kategori sekmeleri, ızgara düzeni, modal detayları) güncelleme.
    *   `Leaderboard` sayfasını daha fazla istatistik (örn: haftalık/aylık liderler) ve filtreleme seçeneğiyle geliştirme.
    *   Yeni bir `Shop` (Mağaza) Livewire bileşeni ve görünümü oluşturma: Ürün listeleme, satın alma işlemleri.
    *   `Inventory` (Envanter) Livewire bileşeni ve görünümünü oluşturma/güncelleme: Kazanılan/satın alınan öğeleri listeleme, kullanma/etkinleştirme işlemleri.
*   **Filament Kaynakları:**
    *   `GamificationGoalResource` ve `GamificationRewardResource`'ı yeni `_config` JSON alanlarını yönetecek şekilde güncelleme (Filament'in JSON editörünü kullanabiliriz).
    *   `UserInventoryResource` için yeni bir Filament kaynağı oluşturma: Kullanıcı envanterini yönetme, öğeleri manuel olarak ekleme/çıkarma.
*   **Arka Plan İşleri (Background Jobs):**
    *   `DailyGoalResetJob`: Günlük görevleri her gün sıfırlayan iş.
    *   `WeeklyGoalResetJob`: Haftalık görevleri her hafta sıfırlayan iş.
    *   `LevelUpCheckJob`: Kullanıcıların seviye atlayıp atlamadığını periyodik olarak kontrol eden ve ödülleri dağıtan iş.
    *   `LeaderboardCacheJob`: Liderlik tablolarını periyodik olarak güncelleyen ve önbelleğe alan iş.
*   **API Entegrasyonları:**
    *   Sosyal medya paylaşım API'leri için entegrasyonlar (mevcut).
    *   Gerekirse harici hizmetlerle (örn: bildirim servisleri) entegrasyonlar.

### 5. Uygulama Adımları (TODO Listesi)

Bu detaylı planı uygulamak için aşağıdaki adımları izleyeceğim:

*   **Adım 1: Veritabanı Şeması Güncellemeleri**
    *   `gamification_goals` tablosuna `goal_type_config` (JSON) alanı eklemek için migration oluştur.
    *   `gamification_rewards` tablosuna `reward_config` (JSON) alanı eklemek için migration oluştur.
    *   `user_inventory` tablosuna `is_active` (boolean) ve `expires_at` (datetime) alanları eklemek için migration oluştur.
    *   Migration'ları çalıştır.
*   **Adım 2: Filament Kaynaklarını Güncelleme**
    *   `GamificationGoalResource`'ı `goal_type_config` JSON alanını yönetecek şekilde güncelle.
    *   `GamificationRewardResource`'ı `reward_config` JSON alanını yönetecek şekilde güncelle.
    *   `UserInventoryResource` için yeni bir Filament kaynağı oluştur ve kullanıcı envanterini yönetme özelliklerini ekle.
*   **Adım 3: Yeni Hedefler ve Ödüller İçin Veri Ekleme**
    *   `GamificationSeeder`'ı yüzlerce yeni hedef ve ödül ile güncelle.
    *   Yeni rozet görsellerini `public/storage/rewards` dizinine ekle.
*   **Adım 4: Kullanıcı Paneli Geliştirmeleri**
    *   `Achievements` sayfasını yeni tasarıma göre (kategori sekmeleri, ızgara düzeni, modal detayları) güncelle.
    *   `Leaderboard` sayfasını daha fazla istatistik ve filtreleme seçeneğiyle geliştir.
    *   Yeni bir `Shop` (Mağaza) Livewire bileşeni ve görünümü oluştur (ürün listeleme, satın alma işlemleri).
    *   `Inventory` (Envanter) Livewire bileşeni ve görünümünü oluştur/güncelle (kazanılan/satın alınan öğeleri listeleme, kullanma/etkinleştirme işlemleri).
    *   `resources/views/components/user-dashboard-layout.blade.php` dosyasına `Shop` ve `Inventory` için menü öğeleri ekle.
    *   Yeni rotaları `routes/web.php` dosyasına ekle.
*   **Adım 5: Olay Dinleyicileri ve Arka Plan İşleri**
    *   Yeni hedefler için gerekli olay dinleyicilerini oluştur ve kaydet.
    *   `DailyGoalResetJob`, `WeeklyGoalResetJob`, `LevelUpCheckJob`, `LeaderboardCacheJob` gibi arka plan işlerini oluştur ve zamanla.
*   **Adım 6: Test ve Doğrulama**
    *   Admin panelinde ve kullanıcı panosunda tüm yeni özelliklerin tasarımlarıyla birlikte doğru görüntülendiğinden ve çalıştığından emin ol.
    *   Tüm gamification özelliklerinin beklendiği gibi çalıştığını doğrula.

Bu planı uygulamak için "Code" moduna geçmem gerekecek.