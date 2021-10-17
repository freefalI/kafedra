<?php

namespace App\Admin\Controllers;

use App\Models\AcademicTitle;
use App\Models\Position;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PositionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Посади';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Position());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('title', __('title'));

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
        $show = new Show(Position::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('title', __('title'));
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
        $form = new Form(new Position);

        $form->display('id', __('ID'));
        $form->text('title', __('title'));

        return $form;
    }
}
