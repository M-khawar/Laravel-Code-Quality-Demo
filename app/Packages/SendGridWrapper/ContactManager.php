<?php

namespace App\Packages\SendGridWrapper;

use SendGrid;

class ContactManager
{
    private SendGrid $sendgrid;
    private SendGrid\Client $client;

    public function __construct()
    {
        $this->sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
        $this->client = $this->sendgrid->client;
    }

    public function addContact($listID, array $contact)
    {
        $request_body = [
            "list_ids" => [$listID],
            "contacts" => array($contact)
        ];
        $request_body = json_decode(json_encode($request_body));
        $response = $this->client->marketing()->contacts()->put($request_body);

        throw_if(!in_array($response->statusCode(), range(201, 299)), $response->body());

        return json_decode($response->body(), true);
    }

    public function getFieldDefinitions()
    {
        $response = $this->client->marketing()->field_definitions()->get();
        throw_if(!in_array($response->statusCode(), range(201, 299)), $response->body());
        return json_decode($response->body(), true);
    }
}
