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
use Illuminate\Support\Arr;

class LeaveController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Вихідні';
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $isAdmin = !auth()->user()->employee;
        $leave = new Leave();
        $grid = new Grid($leave);
        $grid->model()->orderBy('updated_at', 'desc');
        //for not admin users show only their records
        if (!$isAdmin) {
            $employeeId = auth()->user()->employee->id;
            $grid->model()->where('employee_id', $employeeId);
        }

        //TODO filter by name
        $grid->column('id', __('Id'));
        if ($isAdmin) {
            $grid->column('employee', __('employee'))->display(function ($item) {
                return Employee::getFIO($item['name'], $item['surname'], $item['parent_name']);
            });
        }
        $grid->column('title', __('title'));
        $grid->column('type', __('leave_type'));
        $grid->column('date_from', __('date_from'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        })->date();
        // ->date();
        $grid->column('date_to', __('date_to'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        })->date();
        $grid->column('days', __('n_of_days'));
        // $grid->column('reason', __('reason'));
        // $grid->column('is_approved')->bool();
        $states = [
            'on' => ['text' => 'YES'],
            'off' => ['text' => 'NO'],
        ];
        if ($isAdmin) {
            $grid->column('is_approved',__('is_approved'))->switch($states);
        }
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $isAdmin = !auth()->user()->employee;


        $show = new Show(Leave::findOrFail($id));

        $show->id('ID');

        if ($isAdmin) {
            $show->employee('Працівник', function ($user) {

                // $author->setResource('/admin/users');
                $user->id(__('id'));
                $user->fullname(__('fullname'));

                $user->panel()->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
            });
        };
        $show->title(__('title'));
        $show->type(__('leave_type'));
        $show->date_from(__('date_from'));
        $show->date_to(__('date_to'));
        $show->days(__('n_of_days'));
        $show->reason(__('reason'));
        $show->is_approved(__('is_approved'));
        $show->created_at(__('created_at'));

        return $show;
    }


    public function edit($id, Content $content)
    {
        $isAdmin = !auth()->user()->employee;

        if (!$isAdmin) {
            $leave =  Leave::find($id);
            if ($leave->is_approved) {
                return $content
                    ->withError('Не доступно', 'Ви не можете редагувати погоджену заяву');
            }
        }

        return $content

            ->title($this->title())

            ->description($this->description['edit'] ?? trans('admin.edit'))

            ->body($this->form()->edit($id));
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $isAdmin = !auth()->user()->employee;
        $isUpdate = Arr::last(explode('/', request()->getPathInfo())) == 'edit';
        $leave = new Leave();
        if (!$isUpdate)
            $leave->employee_id = auth()->user()->employee->id;
        $form = new Form($leave);

        $form->display('id', __('id'));
        $form->text('title', __('title'));
        $form->date('date_from', __('date_from'));
        $form->date('date_to', __('date_to'));
        $form->radio('type', __('leave_type'))->options([
            Leave::TYPE_DAY_OFF => Leave::TYPE_DAY_OFF,
            Leave::TYPE_SICK_DAY => Leave::TYPE_SICK_DAY,
            Leave::TYPE_VACATION => Leave::TYPE_VACATION,
            Leave::TYPE_BUISINESS_TRIP => Leave::TYPE_BUISINESS_TRIP,
        ]);

        $form->textarea('reason', __('reason'));
        $form->display('created_at',   __('created_at'));


        $states = [
            'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'No', 'color' => 'danger'],
        ];

        if ($isAdmin) {
            $form->switch('is_approved',__('is_approved'))->states($states);
        }

        return $form;
    }


    public function calendar(Content $content)
    {
        $isAdmin = !auth()->user()->employee;

        $items = Leave::query()
            ->when($isAdmin, function ($q) {
                return $q->where('is_approved', 1);
            })
            ->when(!$isAdmin, function ($q) {
                return $q->where('employee_id', auth()->user()->employee->id);
            })
            ->get();
        $events = [];

        foreach ($items as $item) {
            if ($item->type == Leave::TYPE_DAY_OFF) {
                $color = 'green';
            } else if ($item->type == Leave::TYPE_SICK_DAY) {

                $color = 'purple';
            } else if ($item->type == Leave::TYPE_VACATION) {
                $color = null; //default blue
            }
            if ($isAdmin) {
                // $name  = 'Leave for ' . $item->employee->user->username . ' (' . $item->type . ')';
                $name  =  $item->employee-> getUserFio().' ,' .$item->type ;
            } else {
                if ($item->is_approved)
                    $name = $item->title . ' (Не погоджено)';
                else
                    $name = $item->title;
            }
            $events[] = \Calendar::event(
                $name, //event title
                true, //full day event?
                $item->date_from, //start time (you can also use Carbon instead of DateTime)
                $item->date_to, //end time (you can also use Carbon instead of DateTime)
                'leave' . $item->id, //optionally, you can specify an event ID
                [
                    'color' => $color
                ]
            );
        }

        $calendar =  \Calendar::addEvents($events)->setOptions(['lang'=>'uk']);
        return $content
            ->title('Вихідні')
            ->view('admin.calendar', compact('calendar'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        if (request()->has('is_approved')) {
            $ia = request()->input('is_approved');
            if ($ia === 'on') {
                request()->merge(['is_approved' => 1]);
            } else if ($ia == 'off') {
                request()->merge(['is_approved' => 0]);
            }

            Leave::where('id', $id)->update(request()->only(['is_approved']));
        }

        return $this->form()->update($id);
    }
}
