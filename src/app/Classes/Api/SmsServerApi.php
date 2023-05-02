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

    public function sendSms($receiver_name, $receiver_phone, $sender, $message, $message_id, $priority, $priority_slug)
    {
       $response = $this->client->post('send-sms', [
           'headers' => ['Content-type' => 'application/json'],
           'body'    => json_encode([
               'receiver_name'      => $receiver_name,
               'receiver_phone'     => $receiver_phone,
               'sender'             => $sender,
               'message'            => $message,
               'message_id'         => $message_id,
               'priority'           => $priority,
               'priority_slug'      => $priority_slug,
           ])
       ]);

       $data = json_decode($response->getBody(), true);

       return $data;
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