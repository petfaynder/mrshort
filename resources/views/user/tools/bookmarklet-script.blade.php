javascript:(function(){
    var originalUrl = window.location.href;
    var apiUrl = '{{ route("api.shorten") }}'; // API kısaltma route'u
    var apiToken = '{{ Auth::user()->currentAccessToken()->token ?? "YOUR_API_TOKEN" }}'; // Kullanıcının API tokenı

    // API'ye kısaltma isteği gönderme mantığı buraya gelecek
    // fetch veya XMLHttpRequest kullanılabilir
    alert('Bookmarklet çalıştı! URL: ' + originalUrl + ' API URL: ' + apiUrl + ' API Token: ' + apiToken); // Şimdilik sadece alert

})();