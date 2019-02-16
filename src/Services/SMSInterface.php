<?php

namespace Bishopm\Spellmaster\Services;

interface SMSInterface
{
    public function get_credits();

    public function send_message($messages);

}
