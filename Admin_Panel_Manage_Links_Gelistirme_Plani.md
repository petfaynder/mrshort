# Admin Paneli "Manage Links" ve "Hidden Links" Geliştirme Planı

Bu plan, admin panelindeki "Manage Links" sayfasını güncellemek ve "Hidden Links" için ayrı bir sayfa oluşturmak için atılacak adımları özetlemektedir.

## Mevcut "Manage Links" Sayfasını Güncelleme (`app/Filament/Resources/LinkResource.php`)

Mevcut "Manage Links" sayfasının Filament resource dosyası (`app/Filament/Resources/LinkResource.php`) aşağıdaki özelliklere göre güncellenecektir:

*   **`table` metodunu güncelleme:** Aşağıdaki sütunlar tabloya eklenecektir:
    *   Title
    *   Short Link (Altında "Stats" linki ve "Created on: ..." bilgisi ile)
    *   Username (İlgili kullanıcıya link veren bir sütun olabilir)
    *   Created (Oluşturulma tarihi)
    *   Mass Action (Toplu işlemler için placeholder, Filament'in bulk actions özelliği kullanılacak)
    *   Select Action (Her satır için dropdown menüsü)
*   **`filters` metodunu güncelleme:** Aşağıdaki filtreleme ve arama alanları eklenecektir:
    *   Link Id (Text Input)
    *   User Id (Select/TextInput veya ilişki üzerinden arama)
    *   Alias (Text Input)
    *   Advertising Type (Select/Dropdown - Link modelinde bu bilgi varsa)
    *   Title, Desc. or URL (Text Input - Arama)
*   Her link satırı için "Select Action" dropdown menüsüne Edit, Hide, Inactivate, Delete, Delete with stats aksiyonları eklenecektir. Bu aksiyonlar için ilgili Filament aksiyonları veya özel aksiyonlar tanımlanacaktır.
*   Filament'in bulk actions özelliğini kullanarak toplu işlemler için Hide, Inactivate, Delete, Delete with stats seçenekleri eklenecektir.

## "Hidden Links" İçin Ayrı Sayfa Oluşturma

Gizlenmiş linkleri yönetmek için admin panelinde ayrı bir sayfa oluşturulacaktır.

*   **Yeni Sayfa Oluşturma:** `app/Filament/Resources/LinkResource/Pages/ListHiddenLinks.php` adında yeni bir Filament sayfası oluşturulacaktır.
*   Bu sayfada, `LinkResource`'un `getEloquentQuery` metodunu override ederek sadece `hidden` (veya gizli olduğunu belirten ilgili sütun) alanı `true` olan linkleri getiren bir sorgu tanımlanacaktır.
*   Bu sayfadaki tablo sütunları "Manage Links" tablosuna benzer şekilde yapılandırılacaktır.
*   Gizlenmiş linkler tablosuna Göster/Unhide ve Sil aksiyonları eklenecektir.
*   `app/Filament/Resources/LinkResource.php` dosyasındaki `getPages` metoduna `ListHiddenLinks::route('/hidden')` gibi bir tanımlama eklenerek yeni sayfa Filament navigasyonuna dahil edilecektir.

## Tahmini Etkilenecek Dosyalar

*   `app/Filament/Resources/LinkResource.php`
*   `app/Filament/Resources/LinkResource/Pages/ListHiddenLinks.php` (Yeni dosya)
*   Gerekirse ilgili Link modeli veya diğer yardımcı dosyalar.

## Akış Diyagramı

```mermaid
graph TD
    A[Admin Panel] --> B[Link Resource]
    B --> C[List Links Sayfası]
    C --> D{Filtreleme ve Arama}
    D --> E[Link Tablosu]
    E --> F[Her Link İçin Aksiyonlar (Edit, Hide, vb.)]
    E --> G[Toplu Aksiyonlar (Hide, Delete, vb.)]
    F --> H{Stats Aksiyonu}
    H --> I[Kullanıcı Paneli Stats Gösterimi]
    B --> J[List Hidden Links Sayfası]
    J --> K[Gizlenmiş Linkler Tablosu]
    K --> L[Gizlenmiş Link Aksiyonları (Unhide, Delete)]
```

Bu plan, admin panelindeki link yönetimi özelliklerini geliştirmek için bir yol haritası sunmaktadır. Uygulama adımları, Code modunda gerçekleştirilecektir.