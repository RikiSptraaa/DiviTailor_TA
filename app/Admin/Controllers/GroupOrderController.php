<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Group;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Akaunting\Money\Money;
use App\Models\GroupOrder;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use App\Admin\Actions\GroupOrder\Cetak;
use App\Admin\Actions\GroupOrder\Accept;
use App\Admin\Actions\GroupOrder\Decline;
use App\Admin\Actions\GroupOrder\Show as Lihat;
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
            ->body($this->grid(true));
        $content->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid(null));



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

    public function print(GroupOrder $borongan)
    {
        $carbon = new Carbon();
        $pdf = Pdf::loadView('pdf.borongan', compact('borongan', 'carbon'));
        return $pdf->stream('Borongan-' . $borongan->id);
    }


    protected function grid($is_acc = false)
    {
        $grid = new Grid(new GroupOrder());

        if ($is_acc) {
            $grid->setTitle('Borongan Diterima');
            $grid->model()->where('is_acc', 1);
            $grid->actions(function ($actions) {
                //tombol cetak invoice
                $actions->add(new Cetak());
            });
        } else {
            $grid->model()->where('is_acc', null);
            $grid->setTitle('Borongan Baru');
            $grid->actions(function ($actions) {
                $actions->add(new Accept);
                $actions->add(new Decline);
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableTools();
            $grid->disableBatchActions();
            $grid->disablePagination();

            $grid->column('price', __('Total Harga'))->hide();
        }
        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('group.group_name', 'Nama Grup');
            $filter->like('order_kind', 'Jenis Pakaian');
            $filter->date('group_order_date', 'tanggal');
        });

        $grid->column('id', 'id');
        $grid->column('invoice_number', __('Nomor Nota'));
        $grid->user('Daftar Pelanggan')->take(5)->pluck('name')->label();
        $grid->column('group.group_name', __('Nama Grup'));
        $grid->column('group.institute', __('Nama Intansi'));
        $grid->column('group_order_date', __('Tanggal Borongan'));
        $grid->column('order_kind', __('Jenis Pakaian'));
        $grid->column('users_total', __('Total Anggota Pelanggan'));
        $grid->column('price_per_item', __('Harga Per Unit'))->display(function () {
            if ($this->price_per_item === null) {
                return Money::IDR(0, true);
            } else {
                return Money::IDR($this->price_per_item, true);
            }
        });;
        $grid->column('price', __('Total Harga'))->display(function () {
            if ($this->price === null) {
                return Money::IDR(0, true);
            } else {
                return Money::IDR($this->price, true);
            }
        });
        $grid->column('is_acc', __('Status Borongan'))->using([0 => 'Tidak Diterima', 1 => 'Diterima'])->default("Belum Ada Status");

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
        $show->field('group_order_date', __('Tanggal Borongan'));
        $show->field('order_kind', __('Jenis Pakaian'));
        $show->field('users_total', __('Total Anggota Pelanggan'));
        $show->field('price_per_item', __('Harga Per Unit'))->as(function () {
            return Money::IDR($this->price_per_item, true);
        });;
        $show->field('price', __('Total Harga'))->as(function () {
            return Money::IDR($this->price, true);
        });
        $show->field('is_acc', __('Is acc'))->using([0 => 'Ditolak', 1 => 'Diterima']);
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->user('Pelanggan', function ($users) {
            // $comments->resource('/admin/comments');
            $users->id();
            $users->name('Nama');
            $users->phone_number('Nomor Telepon');
            $users->disableExport();
            $users->disableFilter();
            $users->disableTools();
            $users->disableBatchActions();
            $users->disablePagination();
            $users->disableCreateButton();
            $users->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableView();
                $actions->disableEdit();
                $actions->add(new Lihat);
            });
            // $users->content()->limit(10);
            // $users->created_at();
            // $users->updated_at();
        });

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

        $form->hidden('invoice_number', __('Nomor Nota Borongan'))->default('BRG-' . Str::random(5));
        $form->select('group_id', __('Nama Grup'))->options(Group::all()->pluck('group_name', 'id'))->rules(['required']);
        $form->date('group_order_date', __('Tanggal Borongan'))->default(date('Y-m-d'))->rules(['required', 'date']);
        $form->multipleSelect('user', 'Pelanggan')->options(User::all()->pluck('name', 'id'))->rules(['required']);
        $form->text('order_kind', __('Jenis Baju'))->rules(['required']);
        $form->number('users_total', __('Total Anggota Pelanggan'))->rules(['required']);
        $form->currency('price', __('Total Harga'))->symbol('Rp.')->rules('numeric|required');
        $form->currency('price_per_item', __('Harga Per Unit'))->symbol('Rp.')->rules('numeric|required');
        $form->switch('is_acc', __('Is acc'))->disable()->value(true);

        return $form;
    }
}
