<?php

namespace App\Admin\Controllers;

use App\Models\SizeCelana;
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
    protected $title = 'SizeCelana';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SizeCelana());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('lingkar_pinggang', __('Lingkar pinggang'));
        $grid->column('lingkar_pinggul', __('Lingkar pinggul'));
        $grid->column('panjang_celana', __('Panjang celana'));
        $grid->column('panjang_pesak', __('Panjang pesak'));
        $grid->column('lingkar_bawah', __('Lingkar bawah'));
        $grid->column('lingkar_paha', __('Lingkar paha'));
        $grid->column('lingkar_lutut', __('Lingkar lutut'));
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
        $show = new Show(SizeCelana::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('lingkar_pinggang', __('Lingkar pinggang'));
        $show->field('lingkar_pinggul', __('Lingkar pinggul'));
        $show->field('panjang_celana', __('Panjang celana'));
        $show->field('panjang_pesak', __('Panjang pesak'));
        $show->field('lingkar_bawah', __('Lingkar bawah'));
        $show->field('lingkar_paha', __('Lingkar paha'));
        $show->field('lingkar_lutut', __('Lingkar lutut'));
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
        $form = new Form(new SizeCelana());

        $form->number('user_id', __('User id'));
        $form->number('lingkar_pinggang', __('Lingkar pinggang'));
        $form->number('lingkar_pinggul', __('Lingkar pinggul'));
        $form->number('panjang_celana', __('Panjang celana'));
        $form->number('panjang_pesak', __('Panjang pesak'));
        $form->number('lingkar_bawah', __('Lingkar bawah'));
        $form->number('lingkar_paha', __('Lingkar paha'));
        $form->number('lingkar_lutut', __('Lingkar lutut'));

        return $form;
    }
}
