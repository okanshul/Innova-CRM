<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingsChanged
{
    use Dispatchable, SerializesModels;

    public array $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }
}
