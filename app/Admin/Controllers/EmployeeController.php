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
    protected $title = 'Karyawan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Employee());

        $grid->filter(function ($filter) {
            $filter->like('employee_name', 'Nama Karyawan');
        });

        $grid->column('id', __('Id'));
        $grid->column('employee_name', __('Nama Karyawan'));
        $grid->column('phone_number', __('Nomor Telepon'));
        $grid->column('address', __('Alamat'));
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
        $show = new Show(Employee::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('employee_name', __('Nama Karyawan'));
        $show->field('phone_number', __('Nomor Telepon'));
        $show->field('address', __('Alamat'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

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

        $form->text('employee_name', __('Nama Karyawan'))->rules('required|min:4');
        $form->text('phone_number', __('Nomor Telepon'))->rules('required|numeric|:max:14');
        $form->text('address', __('Alamat'))->rules('required|min:5');

        return $form;
    }
}
