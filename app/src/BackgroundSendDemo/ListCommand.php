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

class ListCommand extends AbstractBackgroundSendCommand
{
    protected const NAME = 'background-send:list';
    protected const DESCRIPTION = 'List scheduled sends';

    protected const ARGUMENTS = [
    ];

    public function execute(InputInterface $input, OutputInterface $output): int
    {       
        $workflows = $this->workflowClient
            ->listWorkflowExecutions(
                'WorkflowType="BackgroundSend.send" AND ExecutionStatus="Running"',
                null);
        
        $result = [];
        
        foreach ( $workflows->getIterator() as $workflow ) {
            $result[] = [
                "workflowId" => $workflow->execution->getId(),
                "status" => $workflow->status,
                "user" => $workflow->memo->getValue("user"),
                "messageID" => $workflow->memo->getValue("messageID"),
                "sendTime" => $workflow->memo->getValue("sendTime")
            ];
        }

        $output->writeln(sprintf("Current workflows: %s",
            json_encode($result, JSON_PRETTY_PRINT)));
        
        return self::SUCCESS;
    }
}