<?php
declare(strict_types=1);

namespace Zenstruck\ScheduleBundle\Message;

class RunTaskMessage
{
    public function __construct(
        protected string $taskId,
    ) {
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
