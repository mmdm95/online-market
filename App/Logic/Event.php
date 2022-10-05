<?php

namespace App\Logic;

use Sim\Event\ClosureProvider;
use Sim\Event\Event as TheEvent;
use Sim\Event\EventProvider;

class Event
{
    public function closures(): ClosureProvider
    {
        $closureProvider = new ClosureProvider();

        // add closure (rare cases can go here)
        // ...

        return $closureProvider;
    }

    public function events(): EventProvider
    {
        $eventProvider = new EventProvider();

        // datatable ajax events
        $eventProvider->addEvent(new TheEvent('datatable.ajax:load'));

        // select2 ajax events
        $eventProvider->addEvent(new TheEvent('select2.ajax:load'));

        // general ajax remove events
        $eventProvider->addEvent(new TheEvent('remove.general.ajax:auth'));
        $eventProvider->addEvent(new TheEvent('remove.general.ajax:invalid_id'));
        $eventProvider->addEvent(new TheEvent('remove.general.ajax:not_exists'));
        $eventProvider->addEvent(new TheEvent('remove.general.ajax:failed'));
        $eventProvider->addEvent(new TheEvent('remove.general.ajax:success'));
        $eventProvider->addEvent(new TheEvent('remove.general.ajax:custom_handler'));

        // general ajax form events
        $eventProvider->addEvent(new TheEvent('form.general.ajax:success'));
        $eventProvider->addEvent(new TheEvent('form.general.ajax:warning'));
        $eventProvider->addEvent(new TheEvent('form.general.ajax:error'));

        // general form events
        $eventProvider->addEvent(new TheEvent('form.general:success'));
        $eventProvider->addEvent(new TheEvent('form.general:warning'));
        $eventProvider->addEvent(new TheEvent('form.general:error'));

        // general ajax status change events
        $eventProvider->addEvent(new TheEvent('status.general.ajax:auth'));
        $eventProvider->addEvent(new TheEvent('status.general.ajax:invalid_id'));
        $eventProvider->addEvent(new TheEvent('status.general.ajax:not_exists'));
        $eventProvider->addEvent(new TheEvent('status.general.ajax:failed'));
        $eventProvider->addEvent(new TheEvent('status.general.ajax:success'));
        $eventProvider->addEvent(new TheEvent('status.general.ajax:before_update'));

        // general ajax multi status change events
        $eventProvider->addEvent(new TheEvent('multi.status.general.ajax:invalid_status'));
        $eventProvider->addEvent(new TheEvent('multi.status.general.ajax:invalid_id'));
        $eventProvider->addEvent(new TheEvent('multi.status.general.ajax:not_exists'));
        $eventProvider->addEvent(new TheEvent('multi.status.general.ajax:failed'));
        $eventProvider->addEvent(new TheEvent('multi.status.general.ajax:success'));
        $eventProvider->addEvent(new TheEvent('multi.status.general.ajax:before_update'));

        return $eventProvider;
    }
}
