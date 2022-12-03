<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Task';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Task());

        $grid->column('id', __('Id'));
        $grid->column('handler_id', __('Handler id'));
        $grid->column('order_id', __('Order id'));
        $grid->column('task_status', __('Task status'));
        $grid->column('employee_fee', __('Employee fee'));
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
        $show = new Show(Task::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('handler_id', __('Handler id'));
        $show->field('order_id', __('Order id'));
        $show->field('task_status', __('Task status'));
        $show->field('employee_fee', __('Employee fee'));
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
        $form = new Form(new Task());

        $form->number('handler_id', __('Handler id'));
        $form->number('order_id', __('Order id'));
        $form->switch('task_status', __('Task status'));
        $form->number('employee_fee', __('Employee fee'));

        return $form;
    }
}
