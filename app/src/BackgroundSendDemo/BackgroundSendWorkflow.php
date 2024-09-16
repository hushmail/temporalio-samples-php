<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use Temporal\Workflow;
use Temporal\Samples\BackgroundSendDemo\BackgroundSendWorkflowInterface;
use Temporal\Samples\BackgroundSendDemo\BackgroundSendActivity;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

use Carbon\CarbonInterval;
use Psr\Log\LoggerInterface;
use Temporal\Activity\ActivityOptions;
use Temporal\SampleUtils\Logger;

class BackgroundSendWorkflow implements BackgroundSendWorkflowInterface
{

    private LoggerInterface $logger;

    private $sendActivity;

    public function __construct()
    {
        $this->sendActivity = Workflow::newActivityStub(
            BackgroundSendActivity::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(300))
        );

        $this->logger = new Logger();
    }

    public function send(string $userAlias, string $messageID, string $sendTime)
    {
        $sendTime = new CarbonImmutable($sendTime);

        // Calculate the duration to sleep
        $now = Carbon::now();
        $durationToSleep = $now->diffInSeconds($sendTime);

        // Sleep the workflow until time to send
        $this->logger->debug(sprintf("Will now sleep for %d seconds until %s",
            $durationToSleep, $sendTime->toString()));
        yield Workflow::timer($durationToSleep);
        $this->logger->debug("Now to run the send activity...");
        yield $this->sendActivity->send($userAlias, $messageID,
            $sendTime->format("Y-m-d H:i:s"));
    }
}