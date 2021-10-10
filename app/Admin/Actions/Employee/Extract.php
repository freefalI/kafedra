<?php

namespace App\Admin\Actions\Employee;

use App\Models\Employee;
use App\Models\Leave;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\App;

class Extract extends RowAction
{
    public $name = 'Витяг';

    public function handle(Employee $employee)
    {
        //TODO ossibility to choose month
        $start = now()->startOfMonth();
        $i = clone $start;
        $data = [];
        while (true) {
            $amount = 1;
            $date = $i->format('d-m-Y');

            if ($i->isWeekend()) {
                //skip
            } else {
                $leave = Leave::where('employee_id', $employee->id)->where('is_approved', 1)
                    //reuse code from widget
                    ->where(function ($q) use ($i) {
                        return $q->where(function ($q) use ($i) {
                            return $q->where(function ($q) use ($i) {
                                return $q->where('date_from', '<=', $i->clone()->startOfDay())
                                    ->where('date_to', '>=', $i->clone()->endOfDay());
                            })
                                //if leave is only for today
                                ->orWhere(['date_from' => $i->clone()->startOfDay(), 'date_to' => $i->clone()->startOfDay()]);
                        });
                    })->first();
                if ($leave) {
                    $type = $leave->type;
                    if ($type == Leave::TYPE_DAY_OFF) {
                        $amount = 0;
                    } else {
                        $amount = 1; // $employee->salary_per_day;
                    }
                }
                $data[] =
                    [
                        'date' => $date,
                        'amount' => $amount,
                        'type' => $type ?? 'Work'
                    ];
            }

            $i->addDay();
            // dump($i);
            if ($i > $start->clone()->endOfMonth())
                break;
        }
        $title = "Витяг за {$start->month}, {$start->year} для {$employee->full_name}";
        $pdf = PDF::loadView('admin.extract', compact(['data', 'title']));
        $filename = 'table_'.$employee->id.'.pdf';

        $pdf->save(public_path($filename));

        return $this->response()->success('Success!')->download('/'.$filename);
    }
}
