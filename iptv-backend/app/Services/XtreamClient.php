<?php

namespace App\Services;

/**
 * Cliente HTTP para el protocolo Xtream Codes (player_api.php).
 * Referencia del protocolo: https://xtream-codes.com (API player_api.php)
 */
class XtreamClient
{
    private string $baseUrl;
    private string $username;
    private string $password;

    public function __construct(string $baseUrl, string $username, string $password)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate(): ?array
    {
        return $this->call([]);
    }

    public function getLiveCategories(): array
    {
        return $this->call(['action' => 'get_live_categories']) ?? [];
    }

    public function getLiveStreams(?string $categoryId = null): array
    {
        $params = ['action' => 'get_live_streams'];
        if ($categoryId !== null && $categoryId !== '') {
            $params['category_id'] = $categoryId;
        }
        return $this->call($params) ?? [];
    }

    public function getShortEpg(string $streamId, int $limit = 4): array
    {
        $result = $this->call([
            'action' => 'get_short_epg',
            'stream_id' => $streamId,
            'limit' => $limit,
        ]);
        return $result['epg_listings'] ?? [];
    }

    public function liveStreamUrl(string $streamId, string $extension = 'ts'): string
    {
        return sprintf(
            '%s/live/%s/%s/%s.%s',
            $this->baseUrl,
            rawurlencode($this->username),
            rawurlencode($this->password),
            $streamId,
            $extension
        );
    }

    /**
     * @return array|null null si la conexión/decodificación falla.
     */
    private function call(array $extraParams): ?array
    {
        $params = array_merge([
            'username' => $this->username,
            'password' => $this->password,
        ], $extraParams);

        $url = $this->baseUrl . '/player_api.php?' . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $error) {
            return null;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : null;
    }
}
