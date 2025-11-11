# Kullanıcı Yönetimi Geliştirme Planı

Bu plan, `/admin/users` sayfasında kullanıcı yönetimi özelliklerini geliştirmek için atılacak adımları içermektedir.

## Plan Adımları:

1.  **`UserResource` Dosyasını Bulma ve İnceleme:** `app/Filament/Resources` dizini altında `UserResource.php` dosyasını bulup içeriğini okuyacağım. Bu dosya, kullanıcı listeleme tablosunu, düzenleme formunu ve diğer ilgili ayarları içerecektir.
2.  **Kullanıcı Bilgilerini Görüntüleme:** Kullanıcının kayıt olurken girdiği bilgileri (muhtemelen `User` modelinde bulunan alanlar) listeleme tablosuna ekleyeceğim.
3.  **Kullanıcı Bilgilerini Düzenleme:** Kullanıcı düzenleme formuna, kullanıcının bilgilerini güncelleyebilmek için gerekli alanları ekleyeceğim veya mevcut alanları düzenleyeceğim.
4.  **Kullanıcıya Mesaj Gönderme:** Kullanıcı listeleme tablosuna veya kullanıcı düzenleme sayfasına, seçilen kullanıcıya mesaj göndermek için bir aksiyon (action) ekleyeceğim. Bu aksiyon, bir modal pencere açarak mesaj gönderme formunu gösterebilir.
5.  **Hesap Deaktif Etme:** Kullanıcı listeleme tablosuna veya kullanıcı düzenleme sayfasına, kullanıcının hesabını deaktif etmek için bir aksiyon ekleyeceğim. Bu aksiyon, kullanıcının durumunu güncelleyecektir.
6.  **Hesap Silme:** Kullanıcı listeleme tablosuna veya kullanıcı düzenleme sayfasına, kullanıcının hesabını silmek için bir aksiyon ekleyeceğim. Bu aksiyon, kullanıcı kaydını veritabanından silecektir.
7.  **"Login as User" Özelliği:**
    *   `UserResource` tablosuna veya kullanıcı düzenleme sayfasına "Login as User" adında bir aksiyon ekleyeceğim.
    *   Bu aksiyon, arka planda belirli bir route'a istek gönderecek.
    *   Yeni bir route tanımlayacağım (örneğin `/admin/users/{user}/login-as`). Bu route sadece admin yetkisine sahip kullanıcılar tarafından erişilebilir olacak.
    *   Bu route'u işleyecek bir controller metodu veya closure oluşturacağım.
    *   Bu metodun içinde, mevcut admin oturumunu kapatmadan, istenen kullanıcı olarak oturum açma işlemini gerçekleştireceğim (örneğin `Auth::loginUsingId($userId)` gibi Laravel'in sağladığı yöntemlerle).
    *   Oturum açma işleminden sonra kullanıcıyı kullanıcı dashboard anasayfasına yönlendireceğim.
    *   Aksiyon, bu route'u yeni bir sekmede açacak şekilde yapılandırılacak.

## Planın Görsel Özeti:

```mermaid
graph TD
    A[Başlangıç] --> B{UserResource.php dosyasını bul ve incele};
    B --> C[Kullanıcı Bilgilerini Görüntüleme];
    C --> D[Kullanıcı Bilgilerini Düzenleme];
    D --> E[Kullanıcıya Mesaj Gönderme];
    E --> F[Hesap Deaktif Etme];
    F --> G[Hesap Silme];
    G --> H["Login as User" Özelliği];
    H --> I[Plan Tamamlandı];