<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Payment;
use Encore\Admin\Controllers\AdminController;


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
        $grid->model()->with('order.user');
        $grid->column('id', __('Id'));
        $grid->column('order_id', __('Nomor Nota'))->display(function () {
            return "<a href='/admin/orders/" . $this->order_id . "'>" .  $this->order->invoice_number . "</a>";
        });
        $grid->column('payment_status', __('Status Pembayaran'));
        $grid->column('paid_date', __('Tanggal Pembayaran'))->default('Belum Melakukan Pembayaran')->display(function () {
            // if($this->paid_date == null){}
            return $this->paid_date == null ? "Belum Melakukan Pembayaran" : Carbon::parse($this->paid_date)->dayName . ', ' . Carbon::parse($this->paid_date)->format('d F Y');
        });
        $grid->column('paid_image', __('Bukti Pembayaran'))->downloadable();
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
        });
        $show->field('payment_status', __('Status Pembayaran'));
        $show->field('paid_date', __('Tanggal Pembayaran'))->as(function () {
            return Carbon::parse($this->paid_date)->dayName . ', ' . Carbon::parse($this->paid_date)->format('d F Y');
        });
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
        $form = new Form(new Payment());
        $order = Order::with('user')->get();
        // $option = [];
        // foreach ($order as $orders) {
        //     $option[$orders->id] = $orders->jenis_baju . '-' . $orders->user->name . '-' . $orders->id;
        // }
        $payment_option = [
            'Uang Muka 25%' => 'Uang Muka 25%', 'Uang Muka 50%' => 'Uang Muka 50%', 'Lunas' => 'Lunas', 'Menunggu Pembayaran' => 'Menunggu Pembayaran'
        ];
        $form->select('order_id', __('Nomor Nota'))->options($order->pluck('invoice_number', 'id'))->rules('required');
        $form->select('payment_status', __('Status Pembayaran'))->options($payment_option)->rules('required');
        $form->date('paid_date', __('Tanggal Pembayaran'));
        $form->file('paid_image', __('Bukti Pembayaran'))->move('payments')->uniqueName()->rules('mimes:pdf,png,jpeg,jpg');
        // dd();

        return $form;
    }
}
