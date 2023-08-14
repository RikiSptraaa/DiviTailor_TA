<?php

namespace App\Admin\Controllers;

use App\Admin\AdminExtensions\UserExporter;
use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Pelanggan';

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
        // return redirect('/admin/users');
    }

    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->exporter(new UserExporter());

        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('username', 'Nama Pengguna');
            $filter->like('name', 'Nama Lengkap');
            $filter->like('institute', 'Instansi');
        });

        $grid->column('id', __('ID'))->sortable();
        $grid->column('username', __('Nama Pengguna'));
        $grid->column('name', __('Nama Lengkap'));
        // $grid->column('email', __('E-Mail'));
        $grid->column('phone_number', __('Nomor Telepon'))->display(function () {
            return  "<a href='https://wa.me/62" . $this->phone_number . "' >" . $this->phone_number . "</a>";
        });
        $grid->column('address', __('Alamat'));
        $grid->column('city', __('Kota'));
        $grid->column('institute', __('Instansi'));
        // $grid->column('gender', __('Jenis Kelamin'))->using([0 => 'Perempuan', 1 => 'Laki-Laki']);
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));
        $show->panel()->style('danger');
        $show->field('id', __('ID'));
        $show->field('username', __('Nama Pengguna'));
        $show->field('name', __('Nama'));
        $show->field('email', __('E-Mail'));
        $show->field('phone_number', __('Nomor Telepon'));
        $show->field('address', __('Alamat'));
        $show->field('city', __('Kota'));
        $show->field('institute', __('Instansi'));
        $show->field('gender', __('Jenis Kelamin'))->using([0 => 'Perempuan', 1 => 'Laki-Laki']);
        // $show->field('created_at', __('Daftar Pada'));

        $show->baju('Ukuran Atasan / Baju', function ($baju) {

            $baju->panel()->style('danger');

            $baju->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });

            $baju->jenis_ukuran()->using(config('const.jenis_ukuran'));
            $baju->kode_ukuran()->using(config('const.kode_ukuran'));
            $baju->panjang_baju()->as(function () {
                return "{$this->panjang_baju} cm";
            });
            $baju->lingkar_kerah()->as(function () {
                return "{$this->lingkar_kerah} cm";
            });
            $baju->lingkar_dada()->as(function () {
                return "{$this->lingkar_dada} cm";
            });
            $baju->lingkar_perut()->as(function () {
                return "{$this->lingkar_perut} cm";
            });
            $baju->lingkar_pinggul()->as(function () {
                return "{$this->lingkar_pinggul} cm";
            });
            $baju->lebar_bahu()->as(function () {
                return "{$this->lebar_bahu} cm";
            });
            $baju->panjang_lengan_pendek()->as(function () {
                return "{$this->panjang_lengan_pendek} cm";
            });
            $baju->panjang_lengan_panjang()->as(function () {
                return "{$this->panjang_lengan_panjang} cm";
            });
            $baju->lingkar_lengan_bawah()->as(function () {
                return "{$this->lingkar_lengan_bawah} cm";
            });
            $baju->lingkar_lengan_atas()->as(function () {
                return "{$this->lingkar_lengan_atas} cm";
            });
        });

        $show->celana('Ukuran Bawahan / Celana', function ($celana) {

            $celana->panel()->style('danger');

            $celana->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });

            $celana->jenis_ukuran()->using(config('const.jenis_ukuran'));
            $celana->kode_ukuran()->using(config('const.kode_ukuran'));

            $celana->lingkar_pinggang()->as(function () {
                return "{$this->lingkar_pinggang} cm";
            });
            $celana->lingkar_pinggul()->as(function () {
                return "{$this->lingkar_pinggul} cm";
            });
            $celana->panjang_celana()->as(function () {
                return "{$this->panjang_celana} cm";
            });
            $celana->panjang_pesak()->as(function () {
                return "{$this->panjang_pesak} cm";
            });
            $celana->lingkar_paha()->as(function () {
                return "{$this->lingkar_paha} cm";
            });
            $celana->lingkar_lutut()->as(function () {
                return "{$this->lingkar_lutut} cm";
            });
        });
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
        $form = new Form(new User());

        // $form->display('id', __('ID'));
        $form->text('username', __('Nama Pengguna'))->creationRules('required|unique:users,username|max:10')->updateRules('required|max:10|unique:users,username,{{id}}');
        $form->text('name', __('Nama Lengkap'))->rules('required|max:50');
        $form->email('email', __('E-Mail'))->creationRules('required|email|unique:users,email|max:50')->updateRules('required|max:50|email|unique:users,email,{{id}}');
        $form->text('phone_number', __('Nomor Telepon'))->creationRules('required|max:20|alpha_num|unique:users,phone_number')->updateRules('required|max:20|alpha_num|unique:users,phone_number,{{id}}');
        $form->textarea('address', __('Alamat'))->rules('required|min:4');
        $form->text('city', __('Kota'))->icon('fa-building')->rules('required|min:4|max:30');
        $form->text('institute', __('Instansi'))->icon('fa-institution')->rules('required|min:3|max:30');
        $form->select('gender', __('Jenis Kelamin'))->options([0 => 'Perempuan', 1 => 'Laki-Laki'])->rules('required');
        $form->hidden('password');
        $form->saving(function (Form $form) {
            $len = strlen($form->phone_number);
            $num = substr($form->phone_number, $len-3);
            $pass = Hash::make('password'.$num);
            $form->password = $pass;

        });
        // $form->display('created_at', __('Created At'));
        // $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
