<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Payment;
use Illuminate\Support\Facades\File;
use Encore\Admin\Controllers\AdminController;
use App\Admin\AdminExtensions\PaymentExporter;


class PaymentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Payment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new Payment());
        $grid->exporter(new PaymentExporter());

        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('order.invoice_number', 'Nomor Nota');
            $filter->date('paid_date', 'Tanggal Pembayaran');
        });
        $grid->model()->with('order.user');
        $grid->column('id', __('Id'));
        $grid->column('order_id', __('Nomor Nota'))->display(function () {
            return "<a href='/admin/orders/" . $this->order_id . "'>" .  $this->order->invoice_number . "</a>";
        });
        $grid->column('payment_status', __('Status Pembayaran'))->using([0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran', 4 => 'Menunggu Konfirmasi Pembayaran']);
        $grid->column('paid_date', __('Tanggal Pembayaran'))->default('Belum Melakukan Pembayaran')->display(function () {
            // if($this->paid_date == null){}
            return $this->paid_date == null ? "Belum Melakukan Pembayaran" : Carbon::parse($this->paid_date)->dayName . ', ' . Carbon::parse($this->paid_date)->format('d F Y');
        });
        $grid->column('paid_file', __('Bukti Pembayaran'))->downloadable();
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
        $show = new Show(Payment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Nomor Nota'))->as(function () {
            return "<a href='/admin/orders/'" . $this->order_id . ">" .  $this->order->invoice_number . "</a>";
        })->unescape();
        $show->field('payment_status', __('Status Pembayaran'))->using([0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran', 4 => 'Menunggu Konfirmasi Pembayaran']);
        $show->field('paid_date', __('Tanggal Pembayaran'))->as(function () {
            return Carbon::parse($this->paid_date)->dayName . ', ' . Carbon::parse($this->paid_date)->format('d F Y');
        });

        if(is_null($show->getModel()->paid_file)){
            $show->paid_file('Bukti Pembayaran')->as(function() {
                return 'Belum Ada Upload File Pembayaran';
            });
        }else{
            $show->paid_file()->file();

        }

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
        $form = new Form(new Payment());
        $order = Order::with('user')->get();
        // $option = [];
        // foreach ($order as $orders) {
        //     $option[$orders->id] = $orders->jenis_baju . '-' . $orders->user->name . '-' . $orders->id;
        // }
        $payment_option = [
            0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran', 4 => 'Menunggu Konfirmasi Pembayaran'
        ];
        $form->select('order_id', __('Nomor Nota'))->options($order->pluck('invoice_number', 'id'))->rules('required');
        $form->select('payment_status', __('Status Pembayaran'))->options($payment_option)->rules('required');
        $form->date('paid_date', __('Tanggal Pembayaran'));
        $form->file('paid_file', __('Bukti Pembayaran'))->move('Payments')->uniqueName()->rules('mimes:pdf,png,jpeg,jpg');
        $form->submitted(function (Form $form) {
            $form->ignore('paid_file');

            if(!is_null(request()->file('paid_file'))){
                if(empty($form->model()->get()->toArray())){
                    $filename = md5(request()->file('paid_file')->getClientOriginalName() . time()) . '.' . request()->file('paid_file')->getClientOriginalExtension();
                    request()->file('paid_file')->move(public_path('uploads/payments'), $filename);

                    $form->model()->paid_file = 'payments'.'/' . $filename;
                
                    // $form->paid_file = 'payments'.'/' . $filename;
                }else{
                    $old_file = $form->model()->paid_file;
                    
                    if (File::exists(public_path('uploads').$old_file)) {
                        File::delete(public_path('uploads').$old_file);
                    }

                    $filename = md5(request()->file('paid_file')->getClientOriginalName() . time()) . '.' . request()->file('paid_file')->getClientOriginalExtension();
                    request()->file('paid_file')->move(public_path('uploads/payments'), $filename);

                    $form->model()->paid_file = 'payments'.'/' . $filename;
                    
                    $form->model()->save();
                }
            }
        });

        return $form;
    }
}
