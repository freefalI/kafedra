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
    protected $title = 'Leaves';

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
        $grid->column('type', __('type'));
        $grid->column('date_from', __('date_from'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        })->date();
        // ->date();
        $grid->column('date_to', __('date_to'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        })->date();
        $grid->column('days', __('N of days'));
        // $grid->column('reason', __('reason'));
        // $grid->column('is_approved')->bool();
        $states = [
            'on' => ['text' => 'YES'],
            'off' => ['text' => 'NO'],
        ];
        if ($isAdmin) {
            $grid->column('is_approved')->switch($states);
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
            $show->employee('Employee information', function ($user) {

                // $author->setResource('/admin/users');
                $user->id();
                $user->fullname();

                $user->panel()->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
            });
        };
        $show->title('title');
        $show->type('type');
        $show->date_from('date_from');
        $show->date_to('date_to');
        $show->days();
        $show->reason();
        $show->is_approved();
        $show->created_at();
        //TODO format columns

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

        $form->display('id', 'ID');
        $form->text('title', 'title');
        $form->date('date_from', 'date_from');
        $form->date('date_to', 'date_to');
        $form->radio('type', 'type')->options([
            Leave::TYPE_DAY_OFF => Leave::TYPE_DAY_OFF,
            Leave::TYPE_SICK_DAY => Leave::TYPE_SICK_DAY,
            Leave::TYPE_VACATION => Leave::TYPE_VACATION,
        ]);

        $form->textarea('reason', 'reason');
        $form->display('created_at', 'Created time');
        // $form->display('updated_at','Updated at');
        // $form->checkbox('is_approved')->options(['1' => 'Yes']);


        $states = [
            'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'No', 'color' => 'danger'],
            // 1 => ['text' => 'YES'],
            // 0 => ['text' => 'NO'],
        ];

        if ($isAdmin) {
            $form->switch('is_approved')->states($states);
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
                $name  = 'Leave for ' . $item->employee-> getUserFio(). ' (' . $item->type . ')';
            } else {
                if ($item->is_approved)
                    $name = $item->title . ' (Not approved)';
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

        $calendar =  \Calendar::addEvents($events);
        return $content
            ->title('Leaves calendar')
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
