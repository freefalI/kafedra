<?php

namespace App\Admin\Controllers;

use App\Models\Student;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StudentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Студенти';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Student());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('pib'));
        $grid->column('enter_year', __('enter_year'));
        $grid->column('phone', __('phone'));
        $grid->column('student_id', __('student_id'));
        $grid->column('dob', __('dob'));
        $grid->column('birth_address', __('birth_address'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Student::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name', __('pib'));
        $show->field('enter_year', __('enter_year'));
        $show->field('phone', __('phone'));
        $show->field('student_id', __('student_id'));
        $show->field('dob', __('dob'));
        $show->field('birth_address', __('birth_address'));
        $show->field('parent_phone', __('parent_phone'));
        $show->field('created_at', __('created_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Student);

        $form->display('id', __('ID'));
        $form->text('name', __('pib'));
        $form->date('enter_year', __('enter_year'));
        $form->text('phone', __('phone'));
        $form->text('student_id', __('student_id'));
        $form->date('dob', __('dob'));
        $form->text('birth_address', __('birth_address'));
        $form->text('parent_phone', __('parent_phone'));


        return $form;
    }
}
