<?php

namespace App\Services;

/**
 * ContentDetectionService
 *
 * URL Pattern Matching ile hedef URL'nin içerik kategorisini tespit eder.
 * Kategoriler: adult | gaming | download | video | generic
 */
class ContentDetectionService
{
    /**
     * Kategori için geçerli değerler.
     */
    public const CATEGORIES = ['adult', 'gaming', 'download', 'video', 'generic'];

    /**
     * URL'den kategori tespit et.
     * Önce kalıcı kayıt varsa onu döndür, yoksa pattern matching yap ve kaydet.
     */
    public function detectAndPersist(\App\Models\Link $link): string
    {
        // Önceden tespit edilmişse direkt dön
        if ($link->detected_category) {
            return $link->detected_category;
        }

        $category = $this->detectFromUrl($link->original_url);

        // Kalıcı olarak kaydet
        $link->updateQuietly([
            'detected_category'   => $category,
            'category_detected_at' => now(),
        ]);

        return $category;
    }

    /**
     * URL'den kategori döndür — DB kayıt yapmaz.
     */
    public function detectFromUrl(string $url): string
    {
        $url = strtolower($url);
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? '');
        $full = $host . $path;

        if ($this->matchesAdult($full)) {
            return 'adult';
        }

        if ($this->matchesGaming($full)) {
            return 'gaming';
        }

        if ($this->matchesDownload($full, $path)) {
            return 'download';
        }

        if ($this->matchesVideo($full)) {
            return 'video';
        }

        return 'generic';
    }

    // -------------------------------------------------------------------------
    // Pattern helpers
    // -------------------------------------------------------------------------

    private function matchesAdult(string $full): bool
    {
        $domains = [
            'pornhub', 'xvideos', 'xhamster', 'xnxx', 'redtube', 'youporn',
            'tube8', 'spankbang', 'eporner', 'beeg', 'tnaflix', 'brazzers',
            'bangbros', 'mofos', 'realitykings', 'naughtyamerica', 'faphouse',
            'onlyfans', 'fans.ly', 'fansly', 'manyvids', 'clips4sale',
            'adultempire', 'aebn', 'gamelink', 'adult', 'porn', 'sex', 'xxx',
            'erotic', 'hentai', 'nsfw', 'milf', 'teen18', 'mature', 'fetish',
        ];

        foreach ($domains as $kw) {
            if (str_contains($full, $kw)) {
                return true;
            }
        }

        return false;
    }

    private function matchesGaming(string $full): bool
    {
        $domains = [
            'steam', 'epicgames', 'gog.com', 'origin.com', 'ubisoft',
            'battlenet', 'blizzard', 'ea.com', 'playstationstore', 'xbox',
            'nintendo', 'itch.io', 'gamejolt', 'g2a', 'kinguin', 'cdkeys',
            'fanatical', 'greenmangaming', 'humble', 'roblox', 'minecraft',
            'leagueoflegends', 'fortnite', 'valorant', 'dota2', 'csgo',
            'twitch.tv', 'gaming', 'gamer', 'gamepass', 'playstore',
        ];

        $pathKeywords = [
            '/games/', '/game/', '/gaming/', '/play/', '/dlc/',
            '/esports/', '/tournament/',
        ];

        foreach ($domains as $kw) {
            if (str_contains($full, $kw)) {
                return true;
            }
        }

        $path = parse_url('https://x.com' . $full, PHP_URL_PATH) ?? '';
        foreach ($pathKeywords as $kw) {
            if (str_contains($path, $kw)) {
                return true;
            }
        }

        return false;
    }

    private function matchesDownload(string $full, string $path): bool
    {
        $domains = [
            'mediafire', 'mega.nz', 'mega.co', '1fichier', 'zippyshare',
            'rapidgator', 'uploaded', 'turbobit', 'filefactory', 'depositfiles',
            '4shared', 'sendspace', 'wetransfer', 'files.fm', 'file.io',
            'tera-fic', 'terabox', 'workupload', 'clicknupload', 'gofile',
            'pixeldrain', 'anonfiles', 'bayfiles', 'uptobox', 'userscloud',
            'solidfiles', 'drop.download', 'bowfile', 'mixdrop',
        ];

        $extensions = [
            '.zip', '.rar', '.7z', '.tar', '.gz', '.exe', '.msi', '.dmg',
            '.pdf', '.apk', '.iso', '.torrent', '.bin', '.img',
        ];

        foreach ($domains as $kw) {
            if (str_contains($full, $kw)) {
                return true;
            }
        }

        foreach ($extensions as $ext) {
            if (str_ends_with($path, $ext)) {
                return true;
            }
        }

        return false;
    }

    private function matchesVideo(string $full): bool
    {
        $domains = [
            'youtube.com', 'youtu.be', 'vimeo.com', 'dailymotion', 'rumble.com',
            'odysee.com', 'bitchute', 'peertube', 'brighteon', 'veoh.com',
            'metacafe', 'dailymotion', 'ted.com', 'tiktok.com', 'instagram.com/reel',
            'instagram.com/tv', 'facebook.com/watch', 'fb.watch',
            'netflix.com', 'primevideo', 'hulu.com', 'disneyplus', 'hbomax',
            'paramountplus', 'peacocktv', 'crunchyroll', 'funimation',
            'twitch.tv/videos', 'streamtape', 'videobin', 'vidoza',
        ];

        $pathKeywords = [
            '/watch', '/video/', '/videos/', '/vod/', '/stream/',
            '/episode/', '/series/', '/movie/',
        ];

        foreach ($domains as $kw) {
            if (str_contains($full, $kw)) {
                return true;
            }
        }

        $path = parse_url('https://x.com' . $full, PHP_URL_PATH) ?? '';
        foreach ($pathKeywords as $kw) {
            if (str_contains($path, $kw)) {
                return true;
            }
        }

        return false;
    }
}
