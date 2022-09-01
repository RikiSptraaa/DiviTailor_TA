<?php

namespace App\Admin\Controllers;

use App\Models\GroupOrder;
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
    protected $title = 'GroupOrder';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GroupOrder());

        $grid->column('id', __('Id'));
        $grid->column('group_id', __('Group id'));
        $grid->column('group_order_date', __('Group order date'));
        $grid->column('order_kind', __('Order kind'));
        $grid->column('price', __('Price'));
        $grid->column('is_acc', __('Is acc'));
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
        $show = new Show(GroupOrder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('group_id', __('Group id'));
        $show->field('group_order_date', __('Group order date'));
        $show->field('order_kind', __('Order kind'));
        $show->field('price', __('Price'));
        $show->field('is_acc', __('Is acc'));
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
        $form = new Form(new GroupOrder());

        $form->number('group_id', __('Group id'));
        $form->date('group_order_date', __('Group order date'))->default(date('Y-m-d'));
        $form->date('order_kind', __('Order kind'))->default(date('Y-m-d'));
        $form->number('price', __('Price'));
        $form->switch('is_acc', __('Is acc'));

        return $form;
    }
}
