<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\SizeBaju;
use App\Models\User;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\HasResourceActions;


class SizeBajuController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ukuran Baju';

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
            ->body($this->form($edit = true)->edit($id));
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

    protected function grid()
    {
        $grid = new Grid(new SizeBaju());
        $grid->disableExport();

        // $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            $filter->like('user.name', 'Nama Pelanggan');
        });
        $grid->column('id', __('ID'))->sortable();
        $grid->column('user.name', __('Nama Pelanggan'));
        // $grid->column('panjang_baju', __('Panjang baju'))->display(function () {
        //     return $this->panjang_baju . ' cm';
        // });
        // $grid->column('lingkar_kerah', __('Lingkar kerah'))->display(function () {
        //     return $this->lingkar_kerah . ' cm';
        // });
        // $grid->column('lingkar_dada', __('Lingkar dada'))->display(function () {
        //     return $this->lingkar_dada . ' cm';
        // });
        // $grid->column('lingkar_perut', __('Lingkar perut'))->display(function () {
        //     return $this->lingkar_perut . ' cm';
        // });
        // $grid->column('lingkar_pinggul', __('Lingkar pinggul'))->display(function () {
        //     return $this->lingkar_pinggul . ' cm';
        // });
        // $grid->column('lebar_bahu', __('Lebar bahu'))->display(function () {
        //     return $this->lebar_bahu . ' cm';
        // });
        // $grid->column('panjang_lengan_pendek', __('Panjang lengan pendek'))->display(function () {
        //     return $this->panjang_lengan_pendek . ' cm';
        // });
        // $grid->column('panjang_lengan_panjang', __('Panjang lengan panjang'))->display(function () {
        //     return $this->panjang_lengan_panjang . ' cm';
        // });
        // $grid->column('lingkar_lengan_bawah', __('Lingkar lengan bawah'))->display(function () {
        //     return $this->lingkar_lengan_bawah . ' cm';
        // });
        // $grid->column('lingkar_lengan_atas', __('Lingkar lengan atas'))->display(function () {
        //     return $this->lingkar_lengan_atas . ' cm';
        // });
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
        $show = new Show(SizeBaju::findOrFail($id));



        $show->field('id', __('Id'));
        $show->field('user.name', __('Nama Pelanggan'));
        $show->field('panjang_baju', __('Panjang baju'))->as(function () {
            return "{$this->panjang_baju} cm";
        });
        $show->field('lingkar_kerah', __('Lingkar kerah'))->as(function () {
            return "{$this->lingkar_kerah} cm";
        });
        $show->field('lingkar_dada', __('Lingkar dada'))->as(function () {
            return "{$this->lingkar_dada} cm";
        });
        $show->field('lingkar_perut', __('Lingkar perut'))->as(function () {
            return "{$this->lingkar_perut} cm";
        });
        $show->field('lingkar_pinggul', __('Lingkar pinggul'))->as(function () {
            return "{$this->lingkar_pinggul} cm";
        });
        $show->field('lebar_bahu', __('Lebar bahu'))->as(function () {
            return "{$this->lebar_bahu} cm";
        });
        $show->field('panjang_lengan_pendek', __('Panjang lengan pendek'))->as(function () {
            return "{$this->panjang_lengan_pendek} cm";
        });
        $show->field('panjang_lengan_panjang', __('Panjang lengan panjang'))->as(function () {
            return "{$this->panjang_lengan_panjang} cm";
        });
        $show->field('lingkar_lengan_bawah', __('Lingkar lengan bawah'))->as(function () {
            return "{$this->lingkar_lengan_bawah} cm";
        });
        $show->field('lingkar_lengan_atas', __('Lingkar lengan atas'))->as(function () {
            return "{$this->lingkar_lengan_atas} cm";
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
    protected function form($edit = false)
    {
        $form = new Form(new SizeBaju());
        if ($edit) {
            $form->display('user.name', __('Nama Pelanggan'));
        } else {

            $form->select('user_id', __('Nama Pelanggan'))->options(User::all()->pluck('name', 'id'))->rules('unique:size_bajus,user_id|required');
        }
        $form->number('panjang_baju', __('Panjang baju'))->rules('required|numeric');
        $form->number('lingkar_kerah', __('Lingkar kerah'))->rules('required|numeric');
        $form->number('lingkar_dada', __('Lingkar dada'))->rules('required|numeric');
        $form->number('lingkar_perut', __('Lingkar perut'))->rules('required|numeric');
        $form->number('lingkar_pinggul', __('Lingkar pinggul'))->rules('required|numeric');
        $form->number('lebar_bahu', __('Lebar bahu'))->rules('required|numeric');
        $form->number('panjang_lengan_pendek', __('Panjang lengan pendek'))->rules('required|numeric');
        $form->number('panjang_lengan_panjang', __('Panjang lengan panjang'))->rules('required|numeric');
        $form->number('lingkar_lengan_bawah', __('Lingkar lengan bawah'))->rules('required|numeric');
        $form->number('lingkar_lengan_atas', __('Lingkar lengan atas'))->rules('required|numeric');

        return $form;
    }
}
