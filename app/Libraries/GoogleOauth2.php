<?php

namespace App\Libraries;

/**
 * Google OAuth2 Service Class
 * Emulates the Google SDK Service class for simple OAuth 2.0 flow.
 * Provides access to Google API endpoints in an object-oriented manner.
 */
class GoogleOauth2
{
    protected GoogleClient $client;
    public object $userinfo;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
        
        // Initialize userinfo service
        $this->userinfo = new class($client) {
            protected GoogleClient $client;

            public function __construct(GoogleClient $client)
            {
                $this->client = $client;
            }

            /**
             * Fetch authenticated user's profile information.
             */
            public function get(): object
            {
                return $this->client->getUserInfo();
            }
        };
    }
}
