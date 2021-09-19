<?php

namespace App\Admin\Controllers;

use App\Models\Employee;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EmployeeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Employee';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Employee());

        $grid->id('ID')->sortable();
        $grid->name('Name')->sortable();
        $grid->surname('Surname')->sortable();
        $grid->parent_name('Parent name')->sortable();
        // $grid->position_id('Position id')->sortable();
        // $grid->hire_date('Hire date')->sortable();
        // $grid->created_at('Created at')->sortable();
        // $grid->updated_at('Updated at')->sortable();

        $grid->filter(function($filter){
            $filter->like('name','Name');
            $filter->like('surname','Surname');
            $filter->like('parent_name','Parent name');
            // $filter->equal('position_id','Position id')->integer();
            // $filter->between('hire_date','Hire date')->date();
            // $filter->between('created_at','Created time')->datetime();
        });

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
        $show = new Show(Employee::findOrFail($id));

        $show->id('ID');
        $show->name('Name');
        $show->surname('Surname');
        $show->parent_name('Parent name');
        $show->created_at('Created time');
        // $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Employee());

        $form->display('id','ID');
        $form->text('name','Name');
        $form->text('surname','Surname');
        $form->text('parent_name','Parent name');

        $form->display('created_at','Created time');
        // $form->display('updated_at','Updated at');

        return $form;
    }
}
