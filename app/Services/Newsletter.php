<?php

namespace App\Services;

class Newsletter
{
    public function subscribe(string $email)
    {


        $mailchimp = new  \MailchimpMarketing\ApiClient;

        $mailchimp->setConfig([
            'apiKey' => config('services.mailchimp.key'),
            'server' => 'us10',
        ]);

        return $mailchimp->lists->addListMember('f46c442975', [
            'email_address' => $email,
            'status' => 'subscribed',
        ]);
    }

    public function unsuscribe()
    {
    }
}
