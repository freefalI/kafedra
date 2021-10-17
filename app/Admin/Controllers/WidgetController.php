<?php

namespace App\Admin\Controllers;

use App\Models\Activity;
use App\Models\Certification;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\ScienceDegree;
use App\Models\Student;
use App\Models\Work;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets;
use Illuminate\Support\Facades\DB;

class WidgetController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dashboard';

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
                ->where('is_approved', 1)
                // ->groupBy('employee_id')
                ->count();

            $total = Employee::count();
            $row->column(3, new Widgets\InfoBox(
                'Всього працівників',
                'users',
                'aqua',
                'employees',
                $total
            ));
            $row->column(3, new Widgets\InfoBox(
                'Доступно працівників',
                'users',
                'green',
                'leaves-calendar',
                $total - $notAvailableEmployees
            ));
            // $row->column(3, new Widgets\InfoBox('Articles', 'book', 'yellow', '/demo/articles', '2786'));
            $row->column(3, new Widgets\InfoBox(
                'Заяв на розгляд',
                'file',
                'red',
                'leaves',
                Leave::where('is_approved', '!=', 1)->count()
            )); //TODO leaves where ststus pending


            $row->column(3, new Widgets\InfoBox(
                'Підвищення кваліфікації за рік',
                'book',
                'yellow',
                'certification',
                Certification::where('date', '>',now()->startOfYear())->count()
            ));

            $row->column(3, new Widgets\InfoBox(
                'Видано робіт за рік',
                'book',
                'green',
                'works',
                Work::where('published_at', '>',now()->startOfYear())->count()
            ));

            $row->column(3, new Widgets\InfoBox(
                'Студентів',
                'book',
                'red',
                'students',
                Student::count()
            ));

            //book
            //file
            ///https://fontawesome.com/v4.7/icons/і
        });

        $headers1 = ['Ступінь', 'Кількість'];
        $items = Employee::select('science_degree_id', DB::raw('count(*) as total'))
            // ->leftJoin('science_degrees', 'science_degree_id', '=', 'science_degrees.id')
            ->orderBy('science_degree_id', 'asc')
            ->groupBy('science_degree_id')->with('scienceDegree')->get();
        $rows1 = $items->transform(function ($item, $key) {
            return [$item->scienceDegree->title, $item->total];
        })->toArray();

        $headers2 = ['Звання', 'Кількість'];
        $items = Employee::select('academic_title_id', DB::raw('count(*) as total'))
            // ->leftJoin('academic_titles', 'academic_title_id', '=', 'academic_titles.id')
            ->orderBy('academic_title_id', 'asc')
            ->groupBy('academic_title_id')->with('academicTitle')->get();
        $rows2 = $items->transform(function ($item, $key) {
            return [$item->academicTitle->title, $item->total];
        })->toArray();

        $headers3 = ['Посада', 'Кількість'];
        $items = Employee::select('position_id', DB::raw('count(*) as total'))
            // ->leftJoin('positions', 'position_id', '=', 'positions.id')
            ->orderBy('position_id', 'asc')
            ->groupBy('position_id')->with('position')->get();
        $rows3 = $items->transform(function ($item, $key) {
            return [$item->position->title, $item->total];
        })->toArray();

        $table1 = new Widgets\Table($headers1, $rows1);
        $table2 = new Widgets\Table($headers2, $rows2);
        $table3 = new Widgets\Table($headers3, $rows3);
        $box1 = new Widgets\Box('Наукові ступені', $table1);
        $box2 = new Widgets\Box('Вчені звання', $table2);
        $box3 = new Widgets\Box('Посади', $table3);
        $content->row($box1->solid()->style('primary'));
        $content->row($box2->solid()->style('primary'));
        $content->row($box3->solid()->style('primary'));


        return $content;
    }
}
