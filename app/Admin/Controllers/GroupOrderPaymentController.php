<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\GroupOrder;
use App\Models\GroupOrderPayment;
use Encore\Admin\Controllers\AdminController;
use App\Admin\AdminExtensions\GroupOrderPaymentExporter;


class GroupOrderPaymentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Pembayaran Borongan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GroupOrderPayment());
        $grid->exporter(new GroupOrderPaymentExporter());

        $grid->filter(function ($filter) {

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('groupOrder.invoice_number', 'Nomor Nota');
            $filter->date('paid_date', 'Tanggal Pembayaran');
        });
        // $grid->model()->with('groupOrder', 'user');
        $grid->column('id', __('Id'));
        $grid->column('group_order_id', __('Nomor Nota'))->display(function () {
            return "<a href='/admin/borongan/" . $this->group_order_id . "'>" .  $this->groupOrder->invoice_number . "</a>";
        });
        $grid->column('paid_date', __('Tanggal Pembayaran'))->default('Belum Melakukan Pembayaran')->display(function () {
            // if($this->paid_date == null){}
            return $this->paid_date == null ? "Belum Melakukan Pembayaran" : Carbon::parse($this->paid_date)->dayName . ', ' . Carbon::parse($this->paid_date)->format('d F Y');
        });
        $grid->column('paid_status', __('Status Pembayaran'))->using([0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran']);
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
        $show = new Show(GroupOrderPayment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('group_order_id', __('Nomor Nota'))->as(function () {
            return "<a href='/admin/orders/'" . $this->group_order_id . ">" .  $this->groupOrder->invoice_number . "</a>";
        })->unescape();
        $show->field('paid_status', __('Status Pembayaran'))->using([0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran']);
        $show->field('paid_date', __('Tanggal Pembayaran'))->as(function () {
            return Carbon::parse($this->paid_date)->dayName . ', ' . Carbon::parse($this->paid_date)->format('d F Y');
        });
        $show->paid_file()->file();
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
        $form = new Form(new GroupOrderPayment());
        $order = GroupOrder::all();
        $payment_option = [
            0 => 'Uang Muka 25%', 1 => 'Uang Muka 50%', 2 => 'Lunas', 3 => 'Menunggu Pembayaran'
        ];
        $form->select('group_order_id', __('Nomor Nota'))->options($order->pluck('invoice_number', 'id'))->rules('required');
        $form->select('paid_status', __('Status Pembayaran'))->options($payment_option)->rules('required');
        $form->date('paid_date', __('Tanggal Pembayaran'));
        $form->file('paid_file', __('Bukti Pembayaran'))->move('payments')->uniqueName()->rules('mimes:pdf,png,jpeg,jpg');
        // dd();

        return $form;
    }
}
