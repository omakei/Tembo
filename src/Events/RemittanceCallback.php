<?php

namespace Omakei\Tembo\Events;

use Illuminate\Foundation\Events\Dispatchable;

class RemittanceCallback
{
    use Dispatchable;

    public function __construct(public array $data) {}
}
