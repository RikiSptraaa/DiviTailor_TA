<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\SizeCelana;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Widgets\Collapse;

class SizeCelanaController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ukuran Celana';

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    // protected $description = [
    //     'index'  => 'List Pelanggan',
    //     'show'   => 'Detail Pelanggan',
    //     'edit'   => 'Edit',
    //     'create' => 'Create',
    // ];

    /**
     * Get content title.
     *
     * @return string
     */
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        $content->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid());
        $content->body($this->box('welcome'));

        return $content;
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function box($view, $collapse = false)
    {
        $box = new Box('BOX');
        if ($collapse == true) {
            $box->collapsable();
        }
        $box->style('danger');
        $box->content(view($view));
        return $box;
    }

    public function show($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body($this->form());
        // return redirect('/admin/uk/baju');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SizeCelana());

        $grid->column('id', __('Id'));
        $grid->column('user.name', __('Nama Pelanggan'));
        $grid->column('lingkar_pinggang', __('Lingkar pinggang'))->display(function () {
            return $this->lingkar_pinggang . ' cm';
        });
        $grid->column('lingkar_pinggul', __('Lingkar pinggul'))->display(function () {
            return $this->lingkar_pinggul . ' cm';
        });
        $grid->column('panjang_celana', __('Panjang celana'))->display(function () {
            return $this->panjang_celana . ' cm';
        });
        $grid->column('panjang_pesak', __('Panjang pesak'))->display(function () {
            return $this->panjang_pesak . ' cm';
        });
        $grid->column('lingkar_bawah', __('Lingkar bawah'))->display(function () {
            return $this->lingkar_bawah . ' cm';
        });
        $grid->column('lingkar_paha', __('Lingkar paha'))->display(function () {
            return $this->lingkar_paha . ' cm';
        });
        $grid->column('lingkar_lutut', __('Lingkar lutut'))->display(function () {
            return $this->lingkar_lutut . ' cm';
        });
        $grid->footer(function () {
            return "ABC";
        });
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
        $show = new Show(SizeCelana::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('lingkar_pinggang', __('Lingkar pinggang'))->as(function () {
            return "{$this->lingkar_pinggang} cm";
        });
        $show->field('lingkar_pinggul', __('Lingkar pinggul'))->as(function () {
            return "{$this->lingkar_pinggul} cm";
        });
        $show->field('panjang_celana', __('Panjang celana'))->as(function () {
            return "{$this->panjang_celana} cm";
        });
        $show->field('panjang_pesak', __('Panjang pesak'))->as(function () {
            return "{$this->panjang_pesak} cm";
        });
        $show->field('lingkar_bawah', __('Lingkar bawah'))->as(function () {
            return "{$this->lingkar_bawah} cm";
        });
        $show->field('lingkar_paha', __('Lingkar paha'))->as(function () {
            return "{$this->lingkar_paha} cm";
        });
        $show->field('lingkar_lutut', __('Lingkar lutut'))->as(function () {
            return "{$this->lingkar_lutut} cm";
        });
        // $show->field('created_at', __('Created at'));
        // $show->field('updated_at', __('Updated at'));

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

        $form->select('user_id', __('Nama Pelanggan'))->options(User::all()->pluck('name', 'id'));
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
