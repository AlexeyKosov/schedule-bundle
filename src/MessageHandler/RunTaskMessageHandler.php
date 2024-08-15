<?php
declare(strict_types=1);

namespace Zenstruck\ScheduleBundle\MessageHandler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Zenstruck\ScheduleBundle\Message\RunTaskMessage;
use Zenstruck\ScheduleBundle\Schedule\ScheduleRunner;
use Zenstruck\ScheduleBundle\Schedule\Task\MessageTask;

#[AsMessageHandler]
class RunTaskMessageHandler
{
    public function __construct(
        protected ScheduleRunner $scheduleRunner,
    ) {
    }

    public function __invoke(RunTaskMessage $message): ?string
    {
        $taskId = $message->getTaskId();

        $schedule = $this->scheduleRunner->buildSchedule();

        $originalTask = null;

        foreach ($schedule->all() as $task) {
            if ($task instanceof MessageTask && $task->getId() === $taskId) {
                $originalTask = $task->getOriginalTask();
                break;
            }
        }

        if (!$originalTask) {
            throw new UnrecoverableMessageHandlingException(sprintf('Task %s not found.', $taskId));
        }

        $runner = $this->scheduleRunner->runnerFor($originalTask);

        return $runner($originalTask)->getOutput();
    }
}
