<?php

namespace App\Scheduler;

use App\Message\FindCriteriaResultMessage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('find_criteria_result')]
class FindCriteriaResultSchedulerProvider implements ScheduleProviderInterface
{
    private int $minutes;
    public function __construct(
        ParameterBagInterface $parameterBag,
    )
    {
        $this->minutes = $parameterBag->get('criteria.search.result.schedule');
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
        ->add(RecurringMessage::every($this->minutes." minutes", new FindCriteriaResultMessage()));
    }
}