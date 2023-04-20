<?php namespace Rocketlabs\Sms\App\Classes\Api;

use GuzzleHttp\Client;

class SmsServerApi
{
    const STAGING_SERVER    = 'club24.lv/rl_sms/api/';
    const LIVE_SERVER       = '';

    private $client;

    public function __construct($live = false)
    {
        $base_url   = $live ? self::LIVE_SERVER : self::STAGING_SERVER;

        $this->client = new Client([
            'base_uri'  => $base_url,
            'headers'   => [
                'Accept' => 'application/json'
            ],
            'http_errors' => true
        ]);
    }
    public function getServerStatus()
    {
        $response = $this->client->get('server-status');

        if(!in_array($response->getStatusCode(),[200])){
            $this->handleError($response);
        }

        $data = json_decode($response->getBody(), true);

        return $data['server_status'];
    }

    public function getSms()
    {
    }

    public function sendSms()
    {
    }

    /*
    * Handle Errors
    */
    private function handleError($response)
    {
        $message = '';
        switch($response->getStatusCode()){
            case 400:
            case 404:
            case 412:
            case 500:
                $message = $response->getBody();
                break;
            default:
                $message = 'Unknown error.';
        }

        throw new SmsServerApiException($message);
    }
}