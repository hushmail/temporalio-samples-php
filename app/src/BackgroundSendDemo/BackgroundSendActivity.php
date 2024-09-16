<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use Temporal\Activity\ActivityMethod;
use Psr\Log\LoggerInterface;
use Temporal\SampleUtils\Logger;

class BackgroundSendActivity implements BackgroundSendActivityInterface
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function send(
        string $userAlias,
        string $messageID,
        string $sendTime
    ): void {
        $this->logger->debug(sprintf('Doing the send activity for "%s" "%s" at "%s"',
            $userAlias, $messageID, $sendTime));
    }
}