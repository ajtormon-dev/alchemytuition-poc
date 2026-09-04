<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class BigBlueButtonService
{
    protected string $serverUrl;
    protected string $salt;

    public function __construct()
    {
        $rawUrl = config('bigbluebutton.server_url', '/mock-bbb/');
        
        if (!str_starts_with($rawUrl, 'http://') && !str_starts_with($rawUrl, 'https://')) {
            $rawUrl = url($rawUrl);
        }

        $baseUrl = rtrim($rawUrl, '/');

        if (str_ends_with($baseUrl, '/api')) {
            $this->serverUrl = $baseUrl . '/';
        } else {
            $this->serverUrl = $baseUrl . '/api/';
        }

        $this->salt = config('bigbluebutton.salt', '8cd803242780775d5065e8942b0c3924');
    }

    /**
     * Generate SHA-1 checksum for BigBlueButton API call.
     */
    public function buildChecksum(string $action, string $queryString): string
    {
        return sha1($action . $queryString . $this->salt);
    }

    /**
     * Create a new unique BigBlueButton classroom.
     *
     * @param string|null $meetingName
     * @return array Containing meetingID, moderatorPW, attendeePW, and create response array
     * @throws Exception
     */
    public function createMeeting(?string $meetingName = null): array
    {
        $meetingID = 'room-' . (string) Str::uuid();
        $attendeePW = 'att_' . Str::random(8);
        $moderatorPW = 'mod_' . Str::random(8);
        $name = $meetingName ?? ('Classroom ' . Str::random(5));

        $params = [
            'attendeePW' => $attendeePW,
            'meetingID' => $meetingID,
            'moderatorPW' => $moderatorPW,
            'name' => $name,
        ];

        $queryString = http_build_query($params);
        $checksum = $this->buildChecksum('create', $queryString);
        $url = $this->serverUrl . 'create?' . $queryString . '&checksum=' . $checksum;

        if (str_contains($this->serverUrl, 'mock-bbb') || str_contains($this->serverUrl, '127.0.0.1') || str_contains($this->serverUrl, 'localhost')) {
            $internalRequest = \Illuminate\Http\Request::create($url, 'GET');
            $response = app()->handle($internalRequest);
            $responseBody = $response->getContent();
            $statusCode = $response->getStatusCode();
        } else {
            $httpRes = Http::get($url);
            $statusCode = $httpRes->status();
            $responseBody = $httpRes->body();
        }

        if ($statusCode !== 200) {
            throw new Exception('Failed to connect to BigBlueButton server.');
        }

        $xml = simplexml_load_string($responseBody);
        if ($xml === false || (string)$xml->returncode !== 'SUCCESS') {
            $message = isset($xml->message) ? (string)$xml->message : 'Unknown error during BBB room creation.';
            throw new Exception('BigBlueButton API Error: ' . $message);
        }

        return [
            'meetingID' => $meetingID,
            'moderatorPW' => $moderatorPW,
            'attendeePW' => $attendeePW,
            'name' => $name,
        ];
    }

    /**
     * Generate moderator join URL for a given meetingID and moderator password.
     */
    public function getModeratorJoinUrl(string $meetingID, string $moderatorPW, string $fullName = 'Moderator / Tutor'): string
    {
        $params = [
            'fullName' => $fullName,
            'meetingID' => $meetingID,
            'password' => $moderatorPW,
            'redirect' => 'true',
        ];

        $queryString = http_build_query($params);
        $checksum = $this->buildChecksum('join', $queryString);

        return $this->serverUrl . 'join?' . $queryString . '&checksum=' . $checksum;
    }
}
