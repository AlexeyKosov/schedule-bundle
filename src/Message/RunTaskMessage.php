<?php
declare(strict_types=1);

namespace Zenstruck\ScheduleBundle\Message;

class RunTaskMessage implements \Stringable
{
    public function __construct(
        protected string $taskId,
        protected string $description,
    ) {
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }

    /**
     * This is required to generate a description for `zenstruck/messenger-monitor-bundle`
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->description;
    }
}
