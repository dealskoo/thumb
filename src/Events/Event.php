<?php

namespace Dealskoo\Thumb\Events;

use Dealskoo\Thumb\Models\Thumb;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $thumb;

    public function __construct(Thumb $thumb)
    {
        $this->thumb = $thumb;
    }
}
