<?php

namespace Omakei\Tembo\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UtilityPaymentsCallback
{
    use Dispatchable;

    public function __construct(public array $data) {}
}
