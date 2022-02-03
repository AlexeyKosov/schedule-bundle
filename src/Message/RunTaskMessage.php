<?php
declare(strict_types=1);

namespace Zenstruck\ScheduleBundle\Message;

class RunTaskMessage
{
    /** @var string */
    protected $taskId;

    public function __construct(string $taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
