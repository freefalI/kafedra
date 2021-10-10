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
        $leave = new Leave();
        $grid = new Grid($leave);
        //TODO employee name, filter by name
        $grid->column('id', __('Id'));
        $grid->column('title', __('title'));
        $grid->column('type', __('type'));
        //TODO format as date not datetime
        $grid->column('date_from', __('date_from'))->date();
        $grid->column('date_to', __('date_to'))->date();
        $grid->column('days', __('N of days'));
        $grid->column('reason', __('reason'));
        // $grid->column('is_approved')->bool();
        $states = [
            'on' => ['text' => 'YES'],
            'off' => ['text' => 'NO'],
        ];

        $grid->column('is_approved')->switch($states);
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
        $show = new Show(Leave::findOrFail($id));

        $show->id('ID');
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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $leave = new Leave();
        $leave->employee_id = 1;
        $leave->type = 1;
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

        return $form;
    }


    public function calendar(Content $content)
    {
        $items = Leave::query()
            ->where('is_approved', 1)
            ->get();
        $events = [];

        //TODO better event name
        foreach ($items as $item) {
            if ($item->type == Leave::TYPE_DAY_OFF) {
                $color = 'green';
            } else if ($item->type == Leave::TYPE_SICK_DAY) {

                $color = 'purple';
            } else if ($item->type == Leave::TYPE_VACATION) {
                $color = null; //default blue
            }

            $events[] = \Calendar::event(
                'Leave for user [user]', //event title
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
            Leave::where('id', $id)->update(request()->only(['is_approved']));
        }

        return $this->form()->update($id);
    }
}
