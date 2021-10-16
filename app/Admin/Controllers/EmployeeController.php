<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Employee\Extract;
use App\Models\AcademicTitle;
use App\Models\Employee;
use App\Models\Position;
use App\Models\ScienceDegree;
use Encore\Admin\Auth\Database\Administrator;
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
    protected $title = 'Робітники';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Employee());

        $grid->id('ID')->sortable();
        $grid->column('user.avatar', __('avatar'))->image('',70, 70);

        $grid->name('Name')->sortable();
        $grid->surname('Surname')->sortable();
        $grid->parent_name('Parent name')->sortable();
        // $grid->position_id('Position id')->sortable();
        // $grid->hire_date('Hire date')->sortable();
        // $grid->created_at('Created at')->sortable();
        // $grid->updated_at('Updated at')->sortable();

        $grid->filter(function ($filter) {
            $filter->like('name', 'Name');
            $filter->like('surname', 'Surname');
            $filter->like('parent_name', 'Parent name');
            // $filter->equal('position_id','Position id')->integer();
            // $filter->between('hire_date','Hire date')->date();
            // $filter->between('created_at','Created time')->datetime();

            $options = ScienceDegree::latest()->get()->pluck('title', 'id');
            $filter->in('science_degree_id', 'Науковий ступінь')->checkbox($options);

            $options = AcademicTitle::latest()->get()->pluck('title', 'id');
            $filter->in('academic_title_id', 'Вчене звання')->checkbox($options);

            $options = Position::latest()->get()->pluck('title', 'id');
            $filter->in('position_id', 'Посада')->checkbox($options);
        });

        $grid->actions(function ($actions) {
            $actions->add(new Extract);
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
        // $show->{'user.name'}('Користувач');
        $show->{'scienceDegree.title'}('Науковий ступінь');
        $show->{'academicTitle.title'}('Вчене звання');
        $show->{'position.title'}('Посада');


        // $show->updated_at('Updated at');
        $show->user('User information', function ($user) {
            // $author->setResource('/admin/users');
            $user->id();
            $user->name();
            $user->username();
            // $user->{'user.avatar'}( __('avatar'))->image('',70, 70);
            $user->avatar()->image();
            $user->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });
        });

        $show->works('Роботи', function ($certifications) {
            // $author->setResource('/admin/users');
            $certifications->id();
            $certifications->title();
            $certifications->description();
            $certifications->source();
            $certifications->published_at();

            $certifications->disableCreateButton();
            $certifications->disablePagination();
            $certifications->disableFilter();
            $certifications->disableExport();
            $certifications->disableRowSelector();
            $certifications->disableActions();
            $certifications->disableColumnSelector();

        });
        $show->certifications('Підвищення кваліфікації', function ($certifications) {
            // $author->setResource('/admin/users');
            $certifications->id();
            $certifications->title();
            $certifications->description();
            $certifications->date();

            $certifications->disableCreateButton();
            $certifications->disablePagination();
            $certifications->disableFilter();
            $certifications->disableExport();
            $certifications->disableRowSelector();
            $certifications->disableActions();
            $certifications->disableColumnSelector();
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
        $form = new Form(new Employee());

        $form->display('id', 'ID');
        $form->text('name', 'Name');
        $form->text('surname', 'Surname');
        $form->text('parent_name', 'Parent name');

        $form->display('created_at', 'Created time');
        // $form->display('updated_at','Updated at');

        //TODO except main admin
        $form->select('user_id', 'Користувач')->options(function ($id) {
            $user = Administrator::find($id);
            if ($user) {
                return [$user->id => $user->username];
            }
        })->ajax('/admin/api/users');

        // $form->image('user.avatar');
        $options = ScienceDegree::latest()->get()->pluck('title', 'id');
        $form->radio('science_degree_id', 'Науковий ступінь')->options($options->toArray())->stacked();

        $options = AcademicTitle::latest()->get()->pluck('title', 'id');
        $form->radio('academic_title_id', 'Вчене звання')->options($options->toArray())->stacked();

        $options = Position::latest()->get()->pluck('title', 'id');
        $form->radio('position_id', 'Посада')->options($options->toArray())->stacked();

        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }
}
