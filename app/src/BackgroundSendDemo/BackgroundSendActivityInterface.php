<?php

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;
use Carbon\CarbonInterface;

#[ActivityInterface(prefix: 'BackgroundSend.')]
interface BackgroundSendActivityInterface
{
    public function send(
        string $userAlias,
        string $messageID,
        string $sendTime
    ): void;
}
