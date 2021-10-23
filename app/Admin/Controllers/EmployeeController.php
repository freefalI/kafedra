<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Employee\Extract;
use App\Models\AcademicTitle;
use App\Models\Employee;
use App\Models\Position;
use App\Models\ScienceDegree;
use Carbon\Carbon;
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
    protected $title = 'Працівники';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Employee());

        $grid->id('ID');
        $grid->column('user.avatar', __('avatar'))->image('', 70, 70);

        $grid->column('full_name')->display(function () {
            return $this->full_name;
        });

        $grid->email(__('email'));
        $grid->phone(__('phone'));
        $grid->column('hire_date', __('hire_date'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        });
        $grid->column('dob', __('dob'))->display(function ($name) {
            return Carbon::parse($name)->format('d-m-Y');
        });

        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');
        $grid->column('scienceDegree.title', __('scienceDegree'));
        $grid->column('academicTitle.title', __('academicTitle'));
        $grid->column('position.title', __('position'));
        $grid->filter(function ($filter) {
            $filter->like('name', __('name'));
            $filter->like('surname', __('surname'));
            $filter->like('parent_name', __('parent_name'));
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

        $show->id(__('ID'));
        $show->name(__('name'));
        $show->surname(__('surname'));
        $show->parent_name(__('parent_name'));
        $show->email(__('email'));
        $show->phone(__('phone'));
        $show->hire_date(__('hire_date'));
        $show->dob(__('dob'));
        $show->employment_id(__('employment_id'));
        // $show->field('student_id', __('student_id'));
        // $show->{'user.name'}('Користувач');
        $show->{'scienceDegree.title'}('Науковий ступінь');
        $show->{'academicTitle.title'}('Вчене звання');
        $show->{'position.title'}('Посада');


        // $show->updated_at('Updated at');
        $show->user('Користувач', function ($user) {
            // $author->setResource('/admin/users');
            $user->id(__('id'));
            $user->name(__('name'));
            $user->username(__('username'));
            $user->avatar(__('avatar'))->image();
            $user->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });
        });

        $show->works('Роботи', function ($certifications) {
            // $author->setResource('/admin/users');
            $certifications->id(__('id'));
            $certifications->title(__('title'));
            $certifications->description(__('description'));
            $certifications->source(__('source'));
            $certifications->published_at(__('published_at'));

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
            $certifications->id(__('id'));
            $certifications->title(__('title'));
            $certifications->description(__('description'));
            $certifications->date(__('date'));

            $certifications->disableCreateButton();
            $certifications->disablePagination();
            $certifications->disableFilter();
            $certifications->disableExport();
            $certifications->disableRowSelector();
            $certifications->disableActions();
            $certifications->disableColumnSelector();
        });
        $show->created_at(__('created_at'));

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

        $form->display('id', __('id'));
        $form->text('name', __('name'));
        $form->text('surname', __('surname'));
        $form->text('parent_name', __('parent_name'));
        $form->email('email',__('email'));
        $form->text('phone',__('phone'));
        $form->date('hire_date',__('hire_date'));
        $form->date('dob',__('dob'));
        $form->text('employment_id',__('employment_id'));
        // $form->display('updated_at','Updated at');

        //TODO not return main admin user
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
