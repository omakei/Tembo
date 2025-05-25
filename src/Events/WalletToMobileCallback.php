<?php

namespace Omakei\Tembo\Events;

use Illuminate\Foundation\Events\Dispatchable;

class WalletToMobileCallback
{
    use Dispatchable;

    public function __construct(public array $data) {}
}
