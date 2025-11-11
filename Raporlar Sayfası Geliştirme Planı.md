# Raporlar Sayfası Geliştirme Planı

Bu plan, mevcut raporlar sayfasının (http://127.0.0.1:8000/user/reports) kullanıcı arayüzünü iyileştirmek, tarih aralığı seçimi eklemek, CSV/PDF dışa aktarma özelliklerini entegre etmek ve ek tıklama verilerini (cihaz türü, işletim sistemi, tarayıcı, zaman trendleri, tekil tıklamalar) raporlara dahil etmek için atılacak adımları içermektedir.

## Mevcut Durum Analizi

*   Raporlar sayfası `resources/views/user/reports/index.blade.php` tarafından render edilmekte ve `livewire:user.reports-manager` bileşenini kullanmaktadır.
*   Rapor içeriği `resources/views/livewire/user/reports-manager.blade.php` dosyasında bulunmaktadır.
*   Mevcut raporda ülkelere, linklere ve yönlendiren domainlere göre tıklama verileri gösterilmektedir.
*   Tarih aralığı seçimi ve dışa aktarma özellikleri mevcut değildir.
*   Sayfanın kullanıcı arayüzü görseldeki tasarıma göre iyileştirilmelidir.

## Hedefler

*   Raporlar sayfasının kullanıcı arayüzünü görseldeki tasarıma uygun hale getirmek.
*   Kullanıcıların belirli bir tarih aralığı seçerek raporları filtrelemesini sağlamak.
*   "Son 1 Hafta", "Son 1 Ay", "Son 3 Ay", "Son 1 Yıl", "Tüm Zamanlar" gibi ön tanımlı tarih aralıkları sunmak.
*   Rapor verilerini CSV ve PDF formatında dışa aktarma özelliği eklemek.
*   Raporlara aşağıdaki ek veri noktalarını dahil etmek:
    *   Cihaz Türleri (Masaüstü, Mobil, Tablet vb.)
    *   İşletim Sistemleri (Windows, macOS, Android, iOS vb.)
    *   Tarayıcılar (Chrome, Firefox, Safari, Edge vb.)
    *   Zamana Göre Trendler (Günlük, Haftalık, Aylık tıklama/kazanç grafikleri)
    *   Tekil ve Toplam Tıklama Sayıları

## Veri Toplama ve Tespit Yöntemleri

Cihaz türü, işletim sistemi ve tarayıcı gibi veriler, kullanıcıların tarayıcılarının gönderdiği "User-Agent" HTTP başlığı kullanılarak tespit edilecektir.

*   **User-Agent Başlığı:** Web sunucusuna gelen her istekte bulunan ve istemci (tarayıcı, cihaz, OS) hakkında bilgi içeren bir stringdir.
*   **Tespit Mekanizması:** Link tıklama işlemi sunucu tarafında işlenirken, gelen isteğin "User-Agent" başlığı alınacak ve bu string bir ayrıştırıcı (parser) kütüphanesi yardımıyla analiz edilecektir.
*   **Veritabanı Güncellemesi:** Tespit edilen cihaz türü, işletim sistemi ve tarayıcı bilgileri, tıklama kaydıyla birlikte veritabanındaki ilgili tabloya (muhtemelen `link_clicks`) yeni sütunlar eklenerek kaydedilecektir.
*   **Tekil Tıklama Tespiti:** Tekil tıklamaları ayırt etmek için, tıklama kaydı sırasında kullanıcının oturum bilgileri veya anonimleştirilmiş IP adresi gibi ek veriler kaydedilebilir. Bu veriler daha sonra tekil sayım yapmak için kullanılacaktır.

## Planlanan Adımlar

1.  **Veritabanı Yapısı Güncellemesi:**
    *   `link_clicks` tablosuna veya ilgili tıklama tablosuna `device_type`, `os`, `browser` gibi yeni sütunlar eklenecektir. Tekil tıklama takibi için ek bir sütun veya mekanizma (örneğin, oturum ID'si veya anonim kullanıcı ID'si) düşünülecektir.

2.  **User-Agent Ayrıştırma Kütüphanesi Entegrasyonu:**
    *   Laravel projesine `jenssegers/agent` gibi bir User-Agent ayrıştırma kütüphanesi Composer ile eklenecektir.

3.  **Tıklama Kayıt Mantığı Güncellemesi:**
    *   Link yönlendirme işlemini yapan controller veya ilgili serviste, gelen isteğin "User-Agent" başlığı alınacak, entegre edilen kütüphane ile ayrıştırılacak ve elde edilen cihaz türü, OS, tarayıcı bilgileri tıklama kaydıyla birlikte veritabanına kaydedilecektir. Tekil tıklama takibi için gerekli mantık bu aşamada eklenecektir.

4.  **Kullanıcı Arayüzü (UI) İyileştirmeleri:**
    *   `resources/views/livewire/user/reports-manager.blade.php` dosyası, görseldeki modern ve profesyonel görünüme uygun olarak (Tailwind CSS sınıfları kullanılarak) güncellenecektir.
    *   Sayfanın üst kısmına bir tarih aralığı seçici (date picker) entegre edilecektir.
    *   Tarih aralığı seçicinin yanına ön tanımlı tarih aralıklarını (Son 1 Hafta, Son 1 Ay, Son 3 Ay, Son 1 Yıl, Tüm Zamanlar) içeren bir dropdown menü eklenecektir.
    *   Her bir rapor tablosunun (Ülkeler, Linkler, Yönlendiren Domainler) başlık kısmına veya yakınına CSV ve PDF formatında dışa aktarma butonları eklenecektir.
    *   Cihaz türü, işletim sistemi ve tarayıcı bazında yeni rapor tabloları ve/veya grafikler (pasta grafik veya bar grafik) eklenecektir.
    *   Zamana göre trendleri göstermek için çizgi grafikler eklenecektir.
    *   Link raporlarına tekil tıklama sütunu eklenecektir.

5.  **Livewire Bileşeni (`ReportsManager.php`) Güncellemesi:**
    *   `app/Livewire/User/ReportsManager.php` dosyasına, seçilen başlangıç ve bitiş tarihlerini (`startDate`, `endDate`) ve seçilen ön tanımlı aralığı (`selectedPreset`) tutacak public değişkenler eklenecektir.
    *   Tarih aralığı seçici veya ön tanımlı aralık dropdown'ı değiştiğinde tetiklenecek Livewire metodları (örneğin, `updatedStartDate`, `updatedEndDate`, `updatedSelectedPreset`) tanımlanacaktır.
    *   Bu metodlar içinde, seçilen tarih aralığına göre rapor verilerini yeniden çekme mantığı uygulanacaktır. Ön tanımlı aralık seçildiğinde, bu aralığa karşılık gelen başlangıç ve bitiş tarihleri hesaplanarak `startDate` ve `endDate` değişkenleri güncellenecektir.
    *   Rapor verilerini getiren mevcut sorgular (ülkelere, linklere, yönlendiren domainlere göre), Livewire bileşenindeki `startDate` ve `endDate` değişkenlerine göre filtrelenecek şekilde güncellenecektir.
    *   Yeni eklenen cihaz türü, OS, tarayıcı, zaman trendleri ve tekil tıklama verilerini çekmek ve işlemek için yeni metodlar ve sorgular eklenecektir.

6.  **Dışa Aktarma Fonksiyonelliği Geliştirme:**
    *   `app/Livewire/User/ReportsManager.php` dosyasına `exportCsv()` ve `exportPdf()` adında public metodlar eklenecektir.
    *   Bu metodlar çağrıldığında, o an Livewire bileşeninde bulunan (ve seçili tarih aralığına göre filtrelenmiş) tüm rapor verileri (ülkeler, linkler, yönlendiren domainler, cihazlar, OS, tarayıcılar, trendler) alınacaktır.
    *   CSV dışa aktarma için `maatwebsite/excel` gibi bir kütüphane kullanılarak veriler CSV formatına dönüştürülecek ve kullanıcıya indirilebilir bir yanıt olarak sunulacaktır.
    *   PDF dışa aktarma için `barryvdh/laravel-dompdf` gibi bir kütüphane kullanılarak veriler bir view (Blade şablonu) aracılığıyla PDF formatına dönüştürülecek ve kullanıcıya indirilebilir bir yanıt olarak sunulacaktır.

7.  **Veri Çekme Mantığı Optimizasyonu:**
    *   Raporlar için veritabanından veri çekme sorguları, performans göz önünde bulundurularak optimize edilecektir. Tarih filtrelemesinin ve yeni eklenen sütunların indekslenmiş olması sağlanacaktır.

## Tahmini Süre

Bu planın uygulanması, mevcut kod tabanının karmaşıklığına ve kullanılacak kütüphanelere bağlı olarak değişiklik gösterebilir. Tahmini süre X gün/saat olarak belirlenebilir. (Bu kısım geliştirme aşamasında netleştirilebilir.)

## Kullanılacak Teknolojiler

*   Laravel Livewire
*   Tailwind CSS
*   Chart.js (Mevcut ve yeni grafikler için)
*   Datepicker kütüphanesi (Seçilecek)
*   User-Agent ayrıştırma kütüphanesi (Örn: jenssegers/agent)
*   CSV dışa aktarma kütüphanesi (Örn: maatwebsite/excel)
*   PDF dışa aktarma kütüphanesi (Örn: barryvdh/laravel-dompdf)

## Diagram (Örnek)

```mermaid
graph TD
    A[Kullanıcı Raporlar Sayfasını Açar] --> B(ReportsManager Livewire Bileşeni Yüklenir)
    B --> C{Tarih Aralığı Seçildi mi?}
    C -- Hayır --> D(Varsayılan Tarih Aralığı Kullanılır)
    C -- Evet --> E(Seçilen Tarih Aralığı Kullanılır)
    D --> F(Veritabanından Veri Çekilir - Filtreler Uygulanır)
    F --> G(Veriler İşlenir ve Raporlar Hazırlanır)
    G --> H(Raporlar Kullanıcı Arayüzünde Gösterilir - Tablolar ve Grafikler)
    H --> I{Kullanıcı Dışa Aktarma İstedi mi?}
    I -- Evet --> J{Format Seçimi?}
    J -- CSV --> K(Veriler CSV'ye Dönüştürülür)
    J -- PDF --> L(Veriler PDF'e Dönüştürülür)
    K --> M(CSV Dosyası İndirilir)
    L --> N(PDF Dosyası İndirilir)

    Subgraph Veri Toplama (Tıklama Anı)
        O[Link Tıklaması Gerçekleşir] --> P(User-Agent Başlığı Alınır)
        P --> Q(User-Agent Ayrıştırılır)
        Q --> R(Cihaz, OS, Tarayıcı Bilgileri Elde Edilir)
        R --> S(Veritabanına Kaydedilir - link_clicks tablosu güncellenir)
        S --> T(Tekil Tıklama Kontrolü ve Kaydı)
    End
```

Bu güncellenmiş plan, raporlar sayfasını istenen tüm özelliklerle geliştirmek için daha kapsamlı bir yol haritası sunmaktadır.

Bu plan son haliyle sizin için uygun mu? Uygulama aşamasına geçmek için "Code" moduna geçiş yapalım mı?

<ask_followup_question>
<question>Bu güncellenmiş plan son haliyle sizin için uygun mu? Uygulama aşamasına geçmek için "Code" moduna geçiş yapalım mı?</question>
<follow_up>
<suggest>Plan uygun, uygulama aşamasına geçebiliriz, Code moduna geçelim.</suggest>
<suggest>Plan üzerinde son bir değişiklik yapmak istiyorum.</suggest>
</follow_up>
</ask_followup_question>