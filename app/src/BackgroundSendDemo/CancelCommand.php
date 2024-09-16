<?php

/**
 * Run with: php app.php background-send:cancel test@test.com msg-223322
 */

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Samples\BackgroundSendDemo\AbstractBackgroundSendCommand;

class CancelCommand extends AbstractBackgroundSendCommand
{
    protected const NAME = 'background-send:cancel';
    protected const DESCRIPTION = 'Cancel a send';

    protected const ARGUMENTS = [
        ['user', InputArgument::REQUIRED, 'User alias'],
        ['messageID', InputArgument::REQUIRED, 'Message ID']
    ];

    public function execute(InputInterface $input, OutputInterface $output): int
    {       
        $workflowId = $this->buildWorkflowId($input);

        $output->writeln(sprintf("Workflow ID:\n<info>%s</info>", $workflowId));

        $workflow = $this->workflowClient->newUntypedRunningWorkflowStub(
            $workflowId);

        $output->writeln(sprintf("Cancelling Workflow ID:\n<info>%s</info>", $workflowId));
        
        $workflow->cancel();

        $output->writeln(sprintf("Cancelled Workflow ID:\n<info>%s</info>", $workflowId));
        
        return self::SUCCESS;
    }
}