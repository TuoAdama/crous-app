<?php

namespace App\Scheduler;

use App\Message\FindCriteriaResultMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('find_criteria_result')]
class FindCriteriaResultSchedulerProvider implements ScheduleProviderInterface
{

    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
        ->add(RecurringMessage::cron("*/1 * * * *", new FindCriteriaResultMessage()));
    }
}