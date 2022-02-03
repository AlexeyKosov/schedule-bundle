<?php

namespace Zenstruck\ScheduleBundle\Schedule\Task;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Zenstruck\ScheduleBundle\Message\RunTaskMessage;
use Zenstruck\ScheduleBundle\Schedule\HasMissingDependencyMessage;
use Zenstruck\ScheduleBundle\Schedule\Task;

/**
 * @experimental This is experimental and may experience BC breaks
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class MessageTask extends Task implements HasMissingDependencyMessage
{
    /** @var Task */
    private $originalTask;

    /** @var StampInterface[] */
    private $stamps;

    /**
     * @param Task $originalTask
     * @param StampInterface[] $stamps
     */
    public function __construct(Task $originalTask, array $stamps = [])
    {
        $this->originalTask = $originalTask;
        $this->stamps = $stamps;

        parent::__construct((string)$originalTask);
    }

    /**
     * @return Task
     */
    public function getOriginalTask(): Task
    {
        return $this->originalTask;
    }

    /**
     * @return StampInterface[]
     */
    public function getStamps(): array
    {
        return $this->stamps;
    }

    public function getContext(): array
    {
        $stamps = \array_map(static function(StampInterface $stamp) { return \get_class($stamp); }, $this->stamps);

        $stamps = \array_map(
            static function($stamp) {
                return (new \ReflectionClass($stamp))->getShortName();
            },
            $stamps
        );
        $stamps = \implode(', ', \array_unique($stamps));

        return [
            'Message' => RunTaskMessage::class,
            'Stamps' => $stamps ?: '(none)',
        ];
    }

    public static function getMissingDependencyMessage(): string
    {
        return 'To use the message task you must install symfony/messenger (composer require symfony/messenger) and enable (config path: "zenstruck_schedule.messenger").';
    }
}
