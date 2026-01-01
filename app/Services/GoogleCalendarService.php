<?php
// [file name]: GoogleCalendarService.php
// Location: app/Services/GoogleCalendarService.php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = $this->getGoogleClient();
    }

    public function isConnected()
    {
        return Session::has('google_calendar_token');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback($code)
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                throw new \Exception('Authentication error: ' . ($token['error_description'] ?? $token['error']));
            }
            
            Session::put('google_calendar_token', $token);
            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar callback error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getEvents()
    {
        try {
            if (!$this->isConnected()) {
                return [];
            }

            $token = Session::get('google_calendar_token');
            $this->client->setAccessToken($token);
            
            // Refresh token if expired
            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    Session::put('google_calendar_token', $this->client->getAccessToken());
                } else {
                    throw new \Exception('Session expired. Please reconnect.');
                }
            }
            
            $service = new Calendar($this->client);
            $calendarId = 'primary';
            
            $optParams = [
                'maxResults' => 20,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            ];
            
            $results = $service->events->listEvents($calendarId, $optParams);
            $events = [];
            
            foreach ($results->getItems() as $event) {
                $start = $event->getStart()->dateTime ?: $event->getStart()->date;
                $end = $event->getEnd()->dateTime ?: $event->getEnd()->date;
                
                $events[] = [
                    'id' => $event->getId(),
                    'title' => $event->getSummary(),
                    'description' => $event->getDescription(),
                    'start' => $start,
                    'end' => $end,
                    'location' => $event->getLocation(),
                    'htmlLink' => $event->getHtmlLink(),
                ];
            }
            
            return $events;
        } catch (\Exception $e) {
            Log::error('Event listing error: ' . $e->getMessage());
            return [];
        }
    }

    public function createEvent($eventData)
    {
        try {
            if (!$this->isConnected()) {
                throw new \Exception('Not connected to Google Calendar');
            }

            $token = Session::get('google_calendar_token');
            $this->client->setAccessToken($token);
            
            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    Session::put('google_calendar_token', $this->client->getAccessToken());
                } else {
                    throw new \Exception('Session expired. Please reconnect.');
                }
            }
            
            $service = new Calendar($this->client);
            
            $event = new Event([
                'summary' => $eventData['title'],
                'description' => $eventData['description'] ?? '',
                'location' => $eventData['location'] ?? '',
                'start' => new EventDateTime([
                    'dateTime' => date('c', strtotime($eventData['start'])),
                    'timeZone' => config('app.timezone'),
                ]),
                'end' => new EventDateTime([
                    'dateTime' => date('c', strtotime($eventData['end'])),
                    'timeZone' => config('app.timezone'),
                ]),
            ]);
            
            $calendarId = 'primary';
            $createdEvent = $service->events->insert($calendarId, $event);
            
            return [
                'id' => $createdEvent->getId(),
                'title' => $createdEvent->getSummary(),
                'htmlLink' => $createdEvent->getHtmlLink(),
            ];
        } catch (\Exception $e) {
            Log::error('Event creation error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteEvent($eventId)
    {
        try {
            if (!$this->isConnected()) {
                throw new \Exception('Not connected to Google Calendar');
            }

            $token = Session::get('google_calendar_token');
            $this->client->setAccessToken($token);
            
            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    Session::put('google_calendar_token', $this->client->getAccessToken());
                } else {
                    throw new \Exception('Session expired. Please reconnect.');
                }
            }
            
            $service = new Calendar($this->client);
            $calendarId = 'primary';
            
            $service->events->delete($calendarId, $eventId);
            return true;
        } catch (\Exception $e) {
            Log::error('Event deletion error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function disconnect()
    {
        Session::forget('google_calendar_token');
        return true;
    }

    private function getGoogleClient()
    {
        $client = new Client();
        $client->setApplicationName('Smart');
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope(Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        
        return $client;
    }
}