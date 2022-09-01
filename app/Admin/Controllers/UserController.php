<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Widgets\Box;

use function PHPUnit\Framework\throwException;

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
        $content->body(new Box('Hello'));

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
        // return $content
        //     ->title($this->title())
        //     ->description($this->description['create'] ?? trans('admin.create'))
        //     ->body($this->form());
        return redirect('/admin/users');
    }

    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->disableCreateButton();

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Nama'));
        $grid->column('email', __('E-Mail'));
        $grid->column('phone_number', __('Nomor Telepon'));
        $grid->column('address', __('Alamat'));
        $grid->column('city', __('Kota'));
        $grid->column('institute', __('Instansi'));
        $grid->column('gender', __('Jenis Kelamin'))->using([0 => 'Perempuan', 1 => 'Laki-Laki']);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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

        $show->field('id', __('ID'));
        $show->field('name', __('Nama'));
        $show->field('email', __('E-Mail'));
        $show->field('phone_number', __('Nomor Telepon'));
        $show->field('address', __('Alamat'));
        $show->field('city', __('Kota'));
        $show->field('institute', __('Instansi'));
        $show->field('gender', __('Jenis Kelamin'))->using([0 => 'Perempuan', 1 => 'Laki-Laki']);
        $show->field('created_at', __('Daftar Pada'));
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
        $form = new Form(new User());

        // $form->display('id', __('ID'));
        $form->text('name', __('Nama'))->rules('required');
        $form->email('email', __('E-Mail'))->rules('email|unique:users|required');
        $form->text('phone_number', __('Nomor Telepon'))->icon('fa-mobile')->rules('required|max:14|alpha_num|unique:users');
        $form->text('address', __('Alamat'))->icon('fa-location-arrow')->rules('required|min:4');
        $form->text('city', __('Kota'))->icon('fa-building')->rules('required|min:4');
        $form->text('institute', __('Instansi'))->icon('fa-institution')->rules('required|min:3');
        $form->select('gender', __('Jenis Kelamin'))->icon('fa-gender')->rules('required');
        // $form->display('created_at', __('Created At'));
        // $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
