<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Akaunting\Money\Money;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use App\Admin\Actions\Orders\Cetak;
use App\Admin\Actions\Orders\Accept;

use App\Admin\Actions\Orders\Decline;
use function PHPUnit\Framework\isNull;
use Encore\Admin\Controllers\HasResourceActions;

class OrderController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Pesanan / Orderan';

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
     * Tampilan dari tabel tabel
     * -Tabel Pesanan Diterima
     * -Tabel Pesanan Belum ada status
     * -Tabel Pesanan tidak diterima/ditolak
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
        $content->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid());
        return $content;
    }

    /**
     * Show interface.
     * Tampilan detail pesanan
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
     * Tampilan edit
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
     * Tampilan Buat
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

    //print invoice methot dengan dompdf
    public function print(Order $order)
    {
        $carbon = new Carbon();
        $pdf = Pdf::loadView('pdf.pesanan', compact('order', 'carbon'));
        return $pdf->stream('Pesanan-' . $order->id);
    }

    protected function grid($is_acc = false)
    {
        $grid = new Grid(new Order());

        if ($is_acc) {
            //table pesanan diterima
            $grid->setTitle('Pesanan Diterima');
            $grid->model()->where('is_acc', 1);
            $grid->actions(function ($actions) {
                //tombol cetak invoice
                $actions->add(new Cetak());
            });
        } else if ($is_acc === null) {
            //tabel pesanan belum diterima
            $grid->model()->where('is_acc', null);
            $grid->setTitle('Pesanan Baru');
            $grid->actions(function ($actions) {
                //tambahan tombol terima dan tolak pesanan
                $actions->add(new Accept);
                $actions->add(new Decline);
                //disable semua aksi
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });
            //hide kolom yang tidak perlu
            $grid->column('total_harga', 'total_harga')->hide();
            $grid->column('payment.payment_status', 'Status Pembayaran')->hide();
            $grid->column('task.task_status', 'Status Pengerjaan')->hide();

            //disable semua tombol
            $grid->disableBatchActions();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableTools();
            $grid->disablePagination();
        } else {
            //tabel pesanan ditolak
            $grid->model()->where('is_acc', 0)->whereMonth('order_date', '=', date('m'));
            $grid->setTitle('Pesanan Ditolak');
            $grid->disableBatchActions();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableTools();
            $grid->disablePagination();
        }

        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('invoice_number', 'Nomor Nota');
            $filter->like('user.name', 'Nama Pelanggan');
            $filter->like('jenis_baju', 'Jenis Pakaian');
            $filter->date('order_date', 'Tanggal');
        });



        $grid->column('id', __('Id'));
        $grid->column('invoice_number', __('Nomor Nota'));
        $grid->column('user.name', __('Nama Pelanggan'));
        // $grid->column('is_acc', __('Status Pesanan'))->using([0 => 'Ditolak', 1 => 'Diterima'])->default('Belum Ada Status');
        $grid->column('order_date', __('Tanggal'))->display(function () {
            return Carbon::parse($this->order_date)->dayName . ', ' . Carbon::parse($this->order_date)->format('d F Y');
        });
        $grid->column('jenis_baju', __('Jenis baju'));
        // $grid->column('payment.payment_status', 'Status Pembayaran');
        // $grid->column('task.task_status', 'Status Pengerjaan')->using([0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap'])->default("Belum Dikerjakan");
        //Pakage akaunting untuk currency 
        $grid->column('total_harga', __('Total harga'))->display(function () {
            if ($this->total_harga === null) {
                return Money::IDR(0, true);
            } else {
                return Money::IDR($this->total_harga, true);
            }
        });
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     * Digunakan untuk logika showing detail pesanan
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('invoice_number', __('Nomor Nota'));
        $show->field('user.name', __('Nama Pelanggan'));
        $show->field('is_acc', __('Status Pesanan'))->using([0 => 'Ditolak', 1 => 'Diterima']);
        $show->field('order_date', __('Tanggal'))->as(function () {
            return Carbon::parse($this->order_date)->dayName . ', ' . Carbon::parse($this->order_date)->format('d F Y');
        });
        $show->field('jenis_baju', __('Jenis baju'));
        $show->field('total_harga', __('Total harga'))->as(function () {
            return Money::IDR($this->total_harga, true);
        });
        $show->field('payment.payment_status', 'Status Pembayaran');
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
        $form = new Form(new Order());
        //state dari switch status pesanan
        $states = [
            'on'  => ['value' => 1, 'text' => 'Terima', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Tolak', 'color' => 'danger'],
        ];
        //opsi dari payment status
        $payment_option = [
            'Uang Muka 25%' => 'Uang Muka 25%',
            'Uang Muka 50%' => 'Uang Muka 50%',
            'Lunas' => 'Lunas',
            'Menunggu Pembayaran' => 'Menunggu Pembayaran'
        ];
        $task_option = [
            0 => 'Dalam Pengerjaan',
            1 => 'Sudah Siap'

        ];
        //form tambah dan edit pesanan
        $form->hidden('invoice_number', __('Nomor Nota'))->default('ORD-' . Str::random(5))->rules('required|unique:orders,invoice_number');
        $form->select('user_id', __('Nama Pelanggan'))->options(User::all()->pluck('name', 'id'))->rules('required');
        $form->switch('is_acc', __('Status Pesanan'))->states($states)->rules('required')->default(1)->disable();
        $form->date('order_date', __('Tanggal'))->default(date('Y-m-d'))->rules('required|date');
        $form->date('payment.paid_date', __('Tanggal Bayar'))->default(date('Y-m-d'));
        $form->text('jenis_baju', __('Jenis baju'))->rules('required');
        $form->select('payment.payment_status', 'Status Pembayaran')->options($payment_option)->rules('required');
        $form->currency('total_harga', __('Total harga'))->symbol('Rp.')->rules('required|numeric');

        return $form;
    }
}
