<?php

namespace App\Libraries;

/**
 * A lightweight, dependency-free Google OAuth 2.0 Client for CodeIgniter 4.
 * Resolves path-length issues on Windows by using native CodeIgniter curl request.
 */
class GoogleClient
{
    protected string $clientId = '';
    protected string $clientSecret = '';
    protected string $redirectUri = '';
    protected array $scopes = [];
    protected ?array $accessToken = null;

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function setRedirectUri(string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    public function addScope(string $scope): self
    {
        if (!in_array($scope, $this->scopes)) {
            $this->scopes[] = $scope;
        }
        return $this;
    }

    /**
     * Create the authorization URL for redirection.
     */
    public function createAuthUrl(): string
    {
        $scopeStr = implode(' ', $this->scopes);
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => $scopeStr,
            'access_type'   => 'offline',
            'prompt'        => 'consent'
        ]);
    }

    /**
     * Exchange authorization code for access token.
     */
    public function fetchAccessTokenWithAuthCode(string $code): array
    {
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'code'          => $code,
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri'  => $this->redirectUri,
                    'grant_type'    => 'authorization_code'
                ],
                'http_errors' => false
            ]);

            $body = json_decode($response->getBody(), true);
            if ($response->getStatusCode() !== 200) {
                return [
                    'error'             => 'HTTP_' . $response->getStatusCode(),
                    'error_description' => $body['error_description'] ?? ($body['error'] ?? 'Unknown token exchange error')
                ];
            }

            return $body;
        } catch (\Exception $e) {
            return [
                'error'             => 'exception',
                'error_description' => $e->getMessage()
            ];
        }
    }

    /**
     * Set the access token (string or array).
     */
    public function setAccessToken(array|string $token): self
    {
        $this->accessToken = is_string($token) ? json_decode($token, true) : $token;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Fetch user profile info from Google API.
     */
    public function getUserInfo(): object
    {
        $token = $this->accessToken['access_token'] ?? null;
        if (!$token) {
            throw new \RuntimeException('No access token available to fetch user info.');
        }

        $client = \Config\Services::curlrequest();
        $response = $client->get('https://www.googleapis.com/oauth2/v3/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json'
            ],
            'http_errors' => false
        ]);

        $body = json_decode($response->getBody(), true);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch user info: ' . ($body['error_description'] ?? ($body['error'] ?? 'Unknown Google API response')));
        }

        // Return as a standard object with required attributes emulating Google Client SDK
        return (object)[
            'id'      => $body['sub'] ?? '',
            'email'   => $body['email'] ?? '',
            'name'    => $body['name'] ?? '',
            'picture' => $body['picture'] ?? ''
        ];
    }
}
