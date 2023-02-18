<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Group;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\HasResourceActions;

class GroupController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Grup';

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
        // $content->body();

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
        $grid = new Grid(new Group());

        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('group_code', 'Kode Grup');
            $filter->like('group_name', 'Nama Grup');
            $filter->like('institute', 'Instansi');
        });

        $grid->disableExport();


        $grid->column('id', __('Id'));
        $grid->column('group_code', __('Kode Grup'));
        $grid->column('group_name', __('Nama Grup'));
        $grid->column('group_phone_number', __('Nomor Telepon'));
        $grid->column('institute', __('Instansi'));
        $grid->column('email', __('E-Mail'));
        $grid->column('group_address', __('Alamat'));
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
        $show = new Show(Group::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('group_code', __('Kode Grup'));
        $show->field('group_name', __('Nama Grup'));
        $show->field('group_phone_number', __('Nomor Telepon'));
        $show->field('institute', __('Instansi'));
        $show->field('email', __('E-Mail'));
        $show->field('group_address', __('Alamat'));
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
        $form = new Form(new Group());

        $user = User::whereHas('size_baju')->pluck('name', 'id');

        $form->hidden('group_code', __('Kode Grup'))->default('GRP-' . Str::random(5))->creationRules('required|unique:groups,group_code')->updateRules('required|unique:groups,group_code,{{id}}');
        $form->text('group_name', __('Nama Grup'))->rules('required|max:30');
        $form->select('coordinator', __('Koordinator Grup'))->rules('required|max:30')->options($user);
        $form->text('group_phone_number', __('Nomor Telepon Grup'))->rules('required|string|max:30');
        $form->text('institute', __('Instansi'))->rules('required|max:30');
        $form->email('email', __('E-Mail Grup'))->required()->rules('email|max:50');
        $form->textarea('group_address', __('Alamat'))->required();

        return $form;
    }
}
