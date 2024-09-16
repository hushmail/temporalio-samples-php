<?php

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use Temporal\SampleUtils\Command;
use Symfony\Component\Console\Input\InputInterface;
use Temporal\Client\ClientOptions;

abstract class AbstractBackgroundSendCommand extends Command
{
    protected function buildWorkflowNamespace(InputInterface $input) {

    }

    protected static function buildWorkflowClientOptions(): ?ClientOptions
    {
        return (new ClientOptions())->withNamespace("background-send");
    }

    protected function buildWorkflowId(InputInterface $input) : string {
        return $input->getArgument("user") . "--" . $input->getArgument("messageID");
    }
}