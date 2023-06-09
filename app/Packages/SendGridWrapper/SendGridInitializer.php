<?php

namespace App\Packages\SendGridWrapper;

trait SendGridInitializer
{
    protected function sendgridContactManager()
    {
        return new ContactManager();
    }
}
