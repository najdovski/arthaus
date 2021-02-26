<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterReportsRequest;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    private $reportObj;

    public function __construct()
    {
        $this->reportObj = new ReportRepository();
    }

    /**
     * Display reports
     */
    public function index(FilterReportsRequest $request)
    {
        try {
            $datesWithTotalTime = $this->reportObj->get($request);
        } catch (Exception $exception) {
            $datesWithTotalTime = [];
        }

        if (!config('app.reports_show_empty')) {
            $activitiesNoEmpty = array_filter($datesWithTotalTime, function ($activity) {
                return $activity > 0;
            });
        }

        return view('reports.index', [
            'startDate' => $request->input('start-date'),
            'endDate' => $request->input('end-date'),
            'datesWithTotalTime' => $activitiesNoEmpty ?? $datesWithTotalTime,
        ]);
    }    
}