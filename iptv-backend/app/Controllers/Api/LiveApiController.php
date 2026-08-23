<?php

namespace App\Controllers\Api;

use App\Core\ApiAuth;
use App\Core\Request;
use App\Core\Response;
use App\Models\IptvUser;
use App\Models\Server;
use App\Services\XtreamClient;

class LiveApiController
{
    private function clientForUser(int $userId): ?XtreamClient
    {
        $user = IptvUser::find($userId);
        if (!$user) {
            return null;
        }
        $server = Server::find((int) $user['server_id']);
        if (!$server || $server['status'] !== 'active') {
            return null;
        }
        return new XtreamClient(Server::baseUrl($server), $user['xtream_username'], $user['xtream_password']);
    }

    public function categories(Request $request): void
    {
        $userId = ApiAuth::requireUser($request);
        $client = $this->clientForUser($userId);
        if (!$client) {
            Response::json(['error' => 'Servidor no disponible'], 502);
            return;
        }

        $categories = $client->getLiveCategories();
        Response::json(['categories' => $categories]);
    }

    public function streams(Request $request): void
    {
        $userId = ApiAuth::requireUser($request);
        $client = $this->clientForUser($userId);
        if (!$client) {
            Response::json(['error' => 'Servidor no disponible'], 502);
            return;
        }

        $categoryId = $request->query('category_id');
        $streams = $client->getLiveStreams($categoryId);

        $result = array_map(static function (array $s) {
            return [
                'stream_id' => $s['stream_id'] ?? null,
                'name' => $s['name'] ?? '',
                'stream_icon' => $s['stream_icon'] ?? '',
                'category_id' => $s['category_id'] ?? null,
                'epg_channel_id' => $s['epg_channel_id'] ?? null,
                'num' => $s['num'] ?? null,
            ];
        }, $streams);

        Response::json(['streams' => $result]);
    }

    public function epg(Request $request): void
    {
        $userId = ApiAuth::requireUser($request);
        $client = $this->clientForUser($userId);
        if (!$client) {
            Response::json(['error' => 'Servidor no disponible'], 502);
            return;
        }

        $streamId = (string) $request->query('stream_id', '');
        if ($streamId === '') {
            Response::json(['error' => 'stream_id es obligatorio'], 422);
            return;
        }

        $limit = (int) $request->query('limit', 4);
        $listings = $client->getShortEpg($streamId, $limit);

        $result = array_map(static function (array $item) {
            return [
                'title' => isset($item['title']) ? base64_decode($item['title']) : '',
                'description' => isset($item['description']) ? base64_decode($item['description']) : '',
                'start' => $item['start'] ?? null,
                'end' => $item['end'] ?? null,
                'now_playing' => (bool) ($item['now_playing'] ?? false),
            ];
        }, $listings);

        Response::json(['epg' => $result]);
    }

    public function streamUrl(Request $request): void
    {
        $userId = ApiAuth::requireUser($request);
        $user = IptvUser::find($userId);
        $server = $user ? Server::find((int) $user['server_id']) : null;

        if (!$user || !$server || $server['status'] !== 'active') {
            Response::json(['error' => 'Servidor no disponible'], 502);
            return;
        }

        $streamId = (string) $request->param('stream_id', '');
        if ($streamId === '') {
            Response::json(['error' => 'stream_id es obligatorio'], 422);
            return;
        }

        $client = new XtreamClient(Server::baseUrl($server), $user['xtream_username'], $user['xtream_password']);
        $extension = (string) $request->query('ext', 'ts');

        Response::json(['url' => $client->liveStreamUrl($streamId, $extension)]);
    }
}
