<?php

namespace App\Admin\Controllers;

use App\Models\Employee;
use App\Models\Certification;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Arr;

class CertificationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Підвищення кваліфікації';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $isAdmin = !auth()->user()->employee;
        $leave = new Certification();
        $grid = new Grid($leave);
        $grid->model()->orderBy('date', 'desc');
        //for not admin users show only their records
        if (!$isAdmin) {
            $employeeId = auth()->user()->employee->id;
            $grid->model()->where('employee_id', $employeeId);
        }

        $grid->column('id', __('Id'));
        if ($isAdmin) {
            $grid->column('employee', __('employee'))->display(function ($item) {
                return Employee::getFIO($item['name'], $item['surname'], $item['parent_name']);
            });
        }
        $grid->column('title', __('title'));
        $grid->column('description', __('description'));
        $grid->column('date', __('date'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        })->date();

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


        $show = new Show(Certification::findOrFail($id));

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
        $show->description('description');
        $show->date('date');
        $show->created_at();
        //TODO format columns

        return $show;
    }


    public function edit($id, Content $content)
    {
        $isAdmin = !auth()->user()->employee;

        // if (!$isAdmin) {
        //     $leave =  Work::find($id);
        //     if ($leave->is_approved) {
        //         return $content
        //             ->withError('Не доступно', 'Ви не можете редагувати погоджену заяву');
        //     }
        // }

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
        $leave = new Certification();
        if ($isAdmin) {
        } else {

            if (!$isUpdate)
                $leave->employee_id = auth()->user()->employee->id;
        }

        $form = new Form($leave);

        $form->display('id', 'ID');
        $form->text('title', 'title');
        $form->textarea('description', 'description');
        $form->date('date', 'date');
        $form->display('created_at', 'Created time');
        if ($isAdmin) {
            // Subtable fields
            $form->select('employee_id', 'Працівник')->options(function ($id) {
                $user = Employee::find($id);
                if ($user) {
                    return [$user->id => $user->full_name];
                }
            })->ajax('/admin/api/employees');
        }
        return $form;
    }



    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param int $id
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update($id)
    // {
    //     if (request()->has('is_approved')) {
    //         $ia = request()->input('is_approved');
    //         if ($ia === 'on') {
    //             request()->merge(['is_approved' => 1]);
    //         } else if ($ia == 'off') {
    //             request()->merge(['is_approved' => 0]);
    //         }

    //         Work::where('id', $id)->update(request()->only(['is_approved']));
    //     }

    //     return $this->form()->update($id);
    // }
}
