<?php

namespace App\Admin\Controllers;

use App\Models\Activity;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets;

class WidgetController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Widgets';

    public function index(Content $content)
    {
        $content->title(' ');
        // $content->description('Description...');

        $content->row(function ($row) {
            $notAvailableEmployees = Leave::query()
                ->where(function ($q) {
                    return $q->where(function ($q) {
                        return $q->where(function ($q) {
                            return $q->where('date_from', '<=', now()->startOfDay())
                                ->where('date_to', '>=', now()->endOfDay());
                        })
                            //if leave is only for today
                            ->orWhere(['date_from' => now()->startOfDay(), 'date_to' => now()->startOfDay()]);
                    });
                })
                ->where('is_approved',1)
                // ->groupBy('employee_id')
                ->count();

            $total = Employee::count();
            $row->column(3, new Widgets\InfoBox(
                'Total employees',
                'users',
                'aqua',
                'employees',
                $total
            ));
            $row->column(3, new Widgets\InfoBox(
                'Available employees',
                'users',
                'green',
                'leaves-calendar',
                $total - $notAvailableEmployees
            ));
            // $row->column(3, new Widgets\InfoBox('Articles', 'book', 'yellow', '/demo/articles', '2786'));
            $row->column(3, new Widgets\InfoBox(
                'Pending applications',
                'file',
                'red',
                'leaves',
                Leave::where('is_approved','!=',1)->count()
            )); //TODO leaves where ststus pending

            //book
            //file
            ///https://fontawesome.com/v4.7/icons/і
        });

        return $content;
    }
}
