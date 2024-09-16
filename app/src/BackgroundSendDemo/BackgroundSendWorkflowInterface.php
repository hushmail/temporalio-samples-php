<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use DateTimeInterface;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface BackgroundSendWorkflowInterface
{
    public const WORKFLOW_TYPE = 'BackgroundSend.send';

    #[WorkflowMethod(name: self::WORKFLOW_TYPE)]
    public function send(string $userAlias, string $messageID, string $sendTime);
}