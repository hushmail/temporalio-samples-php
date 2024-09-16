<?php

/**
 * Run with: php app.php background-send:schedule test@test.com msg-223322 "2024-09-04 00:16:00"
 */

declare(strict_types=1);

namespace Temporal\Samples\BackgroundSendDemo;

use Carbon\CarbonInterval;
use Carbon\CarbonImmutable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\Samples\BackgroundSendDemo\AbstractBackgroundSendCommand;

class ScheduleCommand extends AbstractBackgroundSendCommand
{
    protected const NAME = 'background-send:schedule';
    protected const DESCRIPTION = 'Schedule a send';

    protected const ARGUMENTS = [
        ['user', InputArgument::REQUIRED, 'User alias'],
        ['messageID', InputArgument::REQUIRED, 'Message ID'],
        ['sendTime', InputArgument::REQUIRED, 'The time to send "yyyy-mm-dd HH:MM:SS"']
    ];

    public function execute(InputInterface $input, OutputInterface $output): int
    {
       
        $workflowId = $this->buildWorkflowId($input);

        $workflow = $this->workflowClient->newWorkflowStub(
            BackgroundSendWorkflowInterface::class,
            WorkflowOptions::new()
              # The spec currently says max 100 days
              ->withWorkflowExecutionTimeout(CarbonInterval::days(120))
              ->withWorkflowId($workflowId)
              ->withMemo([
                "user" => $input->getArgument("user"),
                "messageID" => $input->getArgument("messageID"),
                "sendTime" => $input->getArgument("sendTime")
              ])
        );

        $output->writeln(sprintf("Workflow ID:\n<info>%s</info>", $workflowId));
       
        $output->writeln("Starting <comment>BackgroundSendWorkflow</comment>... ");

        $run = $this->workflowClient->start($workflow,
            $input->getArgument("user"),
            $input->getArgument("messageID"),
            $input->getArgument("sendTime"));

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>, RunID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
                $run->getExecution()->getRunID(),
            )
        );

        return self::SUCCESS;
    }
}