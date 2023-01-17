<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Employee;
use Akaunting\Money\Money;
use App\Models\GroupOrder;
use Encore\Admin\Widgets\Box;
use App\Models\GroupOrderTask;
use Illuminate\Support\Carbon;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\AdminExtensions\GroupOrderTaskExporter;


class GroupOrderTaskController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Penugasan Borongan';

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
        $grid = new Grid(new GroupOrderTask());
        $grid->exporter(new GroupOrderTaskExporter());


        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            // $filter->date('task_date', 'Tanggal');
            $filter->like('groupOrder.invoice_number', 'Nomor Nota');
            $filter->like('employee.employee_name', 'Nama Karyawan');
            $filter->month('task_date', 'Bulan');
        });

        $grid->column('id', __('Id'));
        $grid->column('employee.employee_name', __('Karyawan'));
        $grid->column('group_order_id', __('Nomor Nota'))->display(function () {
            return "<a href='/admin/borongan/" . $this->group_order_id . "'>" .  $this->groupOrder->invoice_number . "</a>";
        });
        $grid->column('task_date', __('Tanggal Pengerjaan'))->display(function () {
            return Carbon::parse($this->task_started)->dayName . ', ' . Carbon::parse($this->task_started)->format('d F Y');
        });
        $grid->column('task_status', __('Status Penugasan'))->using([0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap']);
        $grid->column('total_unit_asigned', __('Jumlah Unit Yang Dikerjakan'));
        $grid->column('employee_fee', __('Ongkos Karyawan Per Unit'))->display(function () {
            return Money::IDR($this->employee_fee, true);
        });
        $grid->column('employee_fee_total', __('Total Ongkos Karyawan'))->display(function () {
            return Money::IDR($this->employee_fee, true);
        });
        // $grid->column('created_at', __('Tanggal Pengerjaan'));
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
        $show = new Show(GroupOrderTask::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('employee.employee_name', __('Karyawan'));
        $show->field('group_order_id', __('Nomor Nota'))->as(function () {
            return "<a href='/admin/orders/" . $this->group_order_id . "'>" .  $this->groupOrder->invoice_number . "</a>";
        })->unescape();
        $show->field('task_date', __('Tanggal Pengerjaan'))->as(function () {
            return Carbon::parse($this->task_started)->dayName . ', ' . Carbon::parse($this->task_started)->format('d F Y');
        });
        $show->field('task_status', __('Status Penugasan'))->using([0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap']);
        $show->field('total_unit_asigned', __('Jumlah Unit Yang Dikerjakan'));
        $show->field('employee_fee', __('Ongkos Karyawan Per Unit'))->as(function () {
            return Money::IDR($this->employee_fee, true);
        });
        $show->field('employee_fee_total', __('Ttoal Ongkos Karyawan'))->as(function () {
            return Money::IDR($this->employee_fee, true);
        });
        $show->field('note', __('keterangan'));
        // $show->field('created_at', __('Tanggal Pengerjaan'));
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
        $form = new Form(new GroupOrderTask());
        // $order = Order::with('user')->where('is_acc', 1)->get();
        // $option = [];
        // foreach ($order as $orders) {
        //     $option[$orders->id] = $orders->jenis_baju . '-' . $orders->user->name . '-' . $orders->id;
        // }

        $states = [
            'on'  => ['value' => 1, 'text' => 'Sudah Siap', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Dalam Pengerjaan', 'color' => 'danger'],
        ];

        $form->select('handler_id', __('Karyawan'))->options(Employee::all()->pluck('employee_name', 'id'))->rules('required');
        $form->select('group_order_id', __('Nomor Nota Borongan'))->options(GroupOrder::all()->pluck('invoice_number', 'id'))->rules('required');
        $form->date('task_date', __('Tanggal Pengerjaan'))->rules('required|date');
        $form->switch('task_status', __('Status Penugasan'))->states($states)->rules('required');
        $form->number('total_unit_asigned', __('Jumlah Unit Yang Dikerjakan'));
        $form->currency('employee_fee', __('Ongkos Karyawan Per Unit'))->symbol('Rp.')->rules('required|numeric');
        $form->currency('employee_fee_total', __('Total Ongkos Karyawan'))->symbol('Rp.')->rules('required|numeric');
        $form->textarea('note', __('Keterangan'));

        return $form;
    }
}
