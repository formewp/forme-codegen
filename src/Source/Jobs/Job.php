<?php

namespace Forme\CodeGen\Source\Jobs;

use Forme\Framework\Jobs\JobInterface;
use Forme\Framework\Jobs\Queueable;

final class Job //implements JobInterface
{
    // use Queueable; this will get added dynamically

    public function handle(array $args = []): ?string
    {
        return '';
    }
}
