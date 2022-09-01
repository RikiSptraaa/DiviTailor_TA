<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Group;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\GroupOrder;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Encore\Admin\Controllers\HasResourceActions;

class GroupOrderController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Borongan';

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
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body($this->form());
        // return redirect('/admin/users');
    }

    protected function grid()
    {
        $grid = new Grid(new GroupOrder());
        $grid->column('id', 'Id')->expand(function ($model) {
            $users = $model->user()->get()->map(function ($users) {
                $users = [
                    'name' => "<a href='/admin/users/" . $users->id . "'>" . $users->name . "</a>",
                    'phone_number' => $users->phone_number,
                ];
                return $users;
            });
            return new Table(['Nama Pelanggan Borongan', 'Nomor Telepon'], $users->toArray());
        });
        $grid->column('group.group_name', __('Nama Grup'));
        $grid->column('group.institute', __('Nama Intansi'));
        $grid->column('group_order_date', __('Tanggal Borongan'));
        $grid->column('order_kind', __('Jenis Pakaian'));
        $grid->column('price', __('Total Harga'));
        $grid->column('is_acc', __('Status Borongan'))->using([0 => 'Tidak Diterima', 1 => 'Diterima', null => 'Belum Ada Status']);
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

        $form->select('group_id', __('Nama Grup'))->options(Group::all()->pluck('group_name', 'id'));
        $form->date('group_order_date', __('Tanggal Borongan'))->default(date('Y-m-d'));
        $form->multipleSelect('user', 'Pelanggan')->options(User::all()->pluck('name', 'id'));

        $form->text('order_kind', __('Jenis Baju'));
        $form->currency('price', __('Total Harga'))->symbol('Rp.');
        $form->switch('is_acc', __('Is acc'))->default(true);

        return $form;
    }
}
