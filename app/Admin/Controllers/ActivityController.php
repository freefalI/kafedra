<?php

namespace App\Admin\Controllers;

use App\Models\Activity;
use App\Models\Employee;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ActivityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Activity';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Activity());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Activity::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->members('Участники', function ($comments) {
            $comments->employee()->surname();
            $comments->employee()->name();
            $comments->position();
            $comments->created_at();

            // $comments->filter(function ($filter) {
            //     $filter->like('content');
            // });
            $comments->disableCreateButton();
            $comments->disablePagination();
            $comments->disableFilter();
            $comments->disableExport();
            $comments->disableRowSelector();
            $comments->disableActions();
            $comments->disableColumnSelector();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Activity());

        $form->text('name', __('Name'));

        // Subtable fields
        $form->hasMany('members', 'Участники', function (Form\NestedForm $form) {
            $form->select('employee_id', 'Участник')->options(function ($id) {
                $user = Employee::find($id);
                if ($user) {
                    return [$user->id => $user->full_name];
                }
            })->ajax('/admin/api/users');

            $form->text('position', 'Должность');
        });

        return $form;
    }
}
