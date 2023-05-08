<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Log;

class GoogleCalendarController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        if ($request->has('code')) {
            $client->authenticate($request->input('code'));
            $request->session()->put('google_access_token', $client->getAccessToken());
        }

        return redirect('/calendar');
    }

    public function getEvents(Request $request)
    {
        $client = new Google_Client();
        $client->setAccessToken($request->session()->get('google_access_token'));

        if ($client->isAccessTokenExpired()) {
            return redirect('/auth/google');
        }

        $service = new Google_Service_Calendar($client);
        $calendarId = 'primary'; // Change this if you want to access a specific calendar
        $events = $service->events->listEvents($calendarId);
        dd($events);

        return view('calendar', compact('events'));
    }
}
