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
use Illuminate\Support\Facades\Response;
use App\Admin\Actions\GroupOrder\Decline;
use App\Admin\Actions\GroupOrder\Show as Lihat;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\AdminExtensions\GroupOrderExporter;

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

    public function print($id)
    {
        $borongan = GroupOrder::find($id);
        $carbon = new Carbon();
        $pdf = Pdf::loadView('pdf.borongan', compact('borongan', 'carbon'))->setWarnings(false);

        return  $pdf->stream('Borongan-' . $borongan->invoice_number);
    }


    protected function grid($is_acc = false)
    {
        $grid = new Grid(new GroupOrder());
        $grid->exporter(new GroupOrderExporter());


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
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableTools();
            $grid->disableBatchActions();
            $grid->disablePagination();

            $grid->column('price', __('Total Harga'))->hide();
        }
        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('invoice_number', 'Nomor Nota');
            $filter->like('group.group_name', 'Nama Grup');
            $filter->date('group_order_date', 'tanggal');
        });

        $grid->column('id', 'id');
        $grid->column('invoice_number', __('Nomor Nota'));

        // $grid->user('Daftar Pelanggan')->take(5)->pluck('name')->label();
        $grid->column('group.group_name', __('Nama Grup'));
        $grid->column('group.institute', __('Nama Intansi'));
        $grid->column('group_order_date', __('Tanggal Borongan'))->display(function () {
            return Carbon::parse($this->group_order_date)->dayName . ', ' . Carbon::parse($this->group_order_date)->format('d F Y');
        });
        $grid->column('order_kind', __('Jenis Pesanan'));
        $grid->column('tanggal_estimasi', 'Tanggal Estimasi Selesai')->display(function () {
            return Carbon::parse($this->order_date)->dayName . ', ' . Carbon::parse($this->order_date)->translatedFormat('d F Y');
        });
        $grid->column('users_total', __('Jumlah Pelanggan'));
        // $grid->column('Print')->display(function () {
        //     return "<a href='/borongan/cetak/{$this->id}'> Cetak </a>  ";
        // });
        // $grid->column('price_per_item', __('Harga Per Unit'))->display(function () {
        //     if ($this->price_per_item === null) {
        //         return Money::IDR(0, true);
        //     } else {
        //         return Money::IDR($this->price_per_item, true);
        //     }
        // });
        $grid->column('price', __('Total Harga'))->display(function () {
            if ($this->price === null) {
                return Money::IDR(0, true);
            } else {
                return Money::IDR($this->price, true);
            }
        });
        // $grid->column('is_acc', __('Status Borongan'))->using([0 => 'Tidak Diterima', 1 => 'Diterima'])->default("Belum Ada Status");

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
        $show->field('group.group_code', __('Group id'))->as(function () {
            return $this->group->group_code . '(' . $this->group->group_name . ')';
        });
        $show->field('invoice_number', __('No Nota'));
        $show->field('group_order_date', __('Tanggal Borongan'))->as(function () {
            return Carbon::parse($this->group_order_date)->dayName . ', ' . Carbon::parse($this->group_order_date)->translatedFormat('d F Y');
        });
        $show->field('order_kind', __('Jenis Pakaian'));
        $show->field('jenis_pakaian', 'Jenis Pakaian')->using(config("const.jenis_pakaian"));
        $show->field('order_kind', 'Jenis Pesanan');
        $show->field('jenis_kain', 'Jenis Kain')->using(config('const.jenis_kain'));
        $show->field('jenis_panjang', 'Panjang')->using(config('const.jenis_panjang'));
        $show->field('deskripsi_pakaian', 'Deskripsi Pakaian');
        $show->field('tanggal_estimasi', __('Tanggal Estimasi Selesai'))->as(function () {
            return Carbon::parse($this->tanggal_estimasi)->dayName . ', ' . Carbon::parse($this->tanggal_estimasi)->translatedFormat('d F Y');
        });
        $show->field('users_total', __('Jumlah Pelanggan'))->as(function () {
            return $this->users_total . " Orang";
        });
        $show->field('price_per_item', __('Harga Per Unit'))->as(function () {
            return Money::IDR($this->price_per_item, true);
        });;
        $show->field('price', __('Total Harga'))->as(function () {
            return Money::IDR($this->price, true);
        });
        $show->field('is_acc', __('Status'))->using([0 => 'Ditolak', 1 => 'Diterima']);
        // $show->field('created_at', __('Created at'));
        // $show->field('updated_at', __('Updated at'));
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
        $group = Group::all();
        $user_select =[];
        $option = [];

        foreach ($group as $key => $value) {
            $option[$value->id] = $value->group_code . "(" . $value->group_name . "-" . $value->institute . ")";
        }

        $user = User::all();

        foreach($user as $users){
            $user_select[$users->id] = $users->name.'-'.$users->institute;
        }


        $form->hidden('invoice_number', __('Nomor Nota Borongan'))->default('BRG-' . Str::random(5));
        $form->select('group_id', __('Nama Grup'))->options($option)->rules(['required']);
        $form->date('group_order_date', __('Tanggal Borongan'))->default(date('Y-m-d'))->rules(['required', 'date']);
        $form->date('tanggal_estimasi', __('Estimasi Tanggal Jadi'))->rules('required|date');
        $form->multipleSelect('user', 'Pelanggan')->options($user_select)->rules(['required', 'array' ,'min:10']);
        $form->radio('jenis_pakaian', 'Jenis Pakaian')->options(config("const.jenis_pakaian"))->default(0)->rules('required|int');
        $form->text('order_kind', __('Jenis Pesanan'))->placeholder('Contoh:Seragam')->rules(['required']);
        $form->select('jenis_kain', __('Jenis Kain'))->options(config('const.jenis_kain'))->rules('required|int');
        $form->select('jenis_panjang', __('Panjang'))->options(config('const.jenis_panjang'))->rules('required|int');
        $form->textarea('deskripsi_pakaian', __('Deskripsi Pakaian'))->rules('required');
        $form->number('users_total', __('Jumlah Pelanggan'))->rules(['required', 'numeric']);
        $form->currency('price', __('Total Harga'))->symbol('Rp.')->rules('required|numeric');
        $form->currency('price_per_item', __('Harga Per Unit'))->symbol('Rp.')->rules('required|numeric|');
        $form->switch('is_acc', __('Is acc'))->disable()->value(true);


        return $form;
    }
}
