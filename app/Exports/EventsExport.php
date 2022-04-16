<?php

namespace App\Exports;

use App\Exports\DefaultExport;
use App\Models\Event;

class EventsExport extends DefaultExport
{
    public function collection()
    {
        return Event::all();
    }
}
