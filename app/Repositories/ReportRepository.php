<?php

namespace App\Repositories;

use App\Http\Requests\FilterReportsRequest;
use Illuminate\Support\Facades\Session;
use App\Models\Activity;
use App\Helpers\AppHelper;
use Carbon\Carbon;
use Exception;
use DateTime;
use DateInterval;
use DatePeriod;

class ReportRepository
{
    /**
     * Get reports from the DB
     */
    public function get(FilterReportsRequest $request): array
    {
        $startDate = $request && $request->input('start-date') ? $request->input('start-date') : '';
        $endDate = $request && $request->input('end-date') ? $request->input('end-date') : AppHelper::formatDateTimeInput(now(), true);

        try {
            /**
             * Why the query doesn't have a where clause for the dates?
             * Because an activity can be started before the started_at date
             * and end in the range. Hence, will be impossible to identify the
             * time passed on the activity without fetching all the activities.
             * For a larget application, it would be better to add a new table
             * for days/time spent, so the query can be faster.
             * 
             * For the time being, they will be stored in a session
             */
            $activities = Session::get('allActivities')
            ? Session::get('allActivities') :
            Activity::where([
                ['user_id', auth()->user()->id]
            ])
            ->orderBy('started_at', 'ASC')
            ->with('user')
            ->get();

            Session::put('allActivities', $activities);
        } catch (Exception $exception) {
            throw $exception;
        }

        if (sizeof($activities) < 1) {
            return [];
        }

        $datesWithTotalTime = $this->groupByDay(
            $activities,
            $activities[0]->started_at,
            $activities[sizeof($activities) - 1]->finished_at,
            $request->input('start-date'),
            $request->input('end-date')
        );

        return $datesWithTotalTime;
    }

    private function groupByDay($activities, $startDate, $endDate, $inputStartDate, $inputEndDate): array
    {
        // Make an array with all the possible dates between the start and end dates
        $range = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate)
        );

        // Assign the dates as the array keys and zeros as values (total time spent)
        $datesWithTotalTime = [];
        foreach ($range as $date) {
            $datesWithTotalTime[$date->format('Y-m-d')] = 0;
        }

        // Check if there were any activities for a given date
        foreach ($datesWithTotalTime as $date => $totalTimeSpent) {
            foreach ($activities as $activity) {
                $activityStartDate = (new DateTime($activity->started_at))->format('Y-m-d');
                $activityEndDate = (new DateTime($activity->finished_at))->format('Y-m-d');

                // If date is between start and end, add 1440 minutes (24h)
                if (($date > $activityStartDate) && ($date < $activityEndDate)) {
                    $datesWithTotalTime[$date] = 24 * 60;
                    // if the date is equal to start or end date, calculate how many minutes
                    // the activity has taken
                } else if ($activityStartDate === $date || $activityEndDate === $date) {
                    $endTime = strtotime($activity->finished_at);
                    $startTime = strtotime($activity->started_at);
                    $timeDifferenceMinutes = ($endTime - $startTime) / 60;

                    // if the difference is > or = to 24h, calculate by the start or end of the day
                    if ($timeDifferenceMinutes >= 1440) {
                        if ($activityStartDate === $date) {
                            $endOfTheDay = (new Carbon($date))->addDays(1)->subSecond();
                            $timeDifferenceMinutes = (strtotime($endOfTheDay) - $startTime) / 60;
                        } elseif ($activityEndDate === $date) {
                            $startOfTheDay = (new Carbon($date));
                            $timeDifferenceMinutes = ($endTime - strtotime($startOfTheDay)) / 60;
                        }
                    }

                    $datesWithTotalTime[$date] += $timeDifferenceMinutes;
                }
            }
        }

        $filteredDatesWithTotalTime = array_filter($datesWithTotalTime, function ($date) use ($inputStartDate, $inputEndDate) {
            $inputStartDate = (new DateTime($inputStartDate))->format('Y-m-d');
            $inputEndDate = (new DateTime($inputEndDate))->format('Y-m-d');

            if (($date >= $inputStartDate) && ($date <= $inputEndDate)) {
                return true;
            }
        }, ARRAY_FILTER_USE_KEY);

        return $filteredDatesWithTotalTime;
    }
}
