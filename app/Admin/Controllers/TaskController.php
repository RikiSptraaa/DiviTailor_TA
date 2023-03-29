<?php

namespace App\Admin\Controllers;

use App\Models\Task;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Employee;
use Akaunting\Money\Money;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Carbon;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use App\Admin\AdminExtensions\TaskExporter;
use Encore\Admin\Controllers\HasResourceActions;


class TaskController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Penugasan';

    protected $selectOrder = [];

    public function __construct(){
        $order = Order::all();

        foreach ($order as $key => $value) {
            $this->selectOrder[$value->id] = $value->user->name.' ('.$value->invoice_number.') ('.$value->jenis_pembuatan.')'; 
        }
    }

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
        $grid = new Grid(new Task());
        $grid->exporter(new TaskExporter());


        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('order_id', 'Nomor Nota')->select($this->selectOrder);
            $filter->like('employee.employee_name', 'Nama Karyawan');
            $filter->month('task_started', 'Bulan');
            // $filter->date('created_at', 'Tanggal');
        });

        $grid->column('id', __('Id'));
        $grid->column('employee.employee_name', __('Karyawan'));
        $grid->column('order_id', __('Nomor Nota'))->display(function () {
            return "<a href='/admin/orders/" . $this->order_id . "'>" .  $this->order->invoice_number . "</a>";
        });
        $grid->column('task_started', __('Tanggal Pengerjaan'))->display(function () {
            return Carbon::parse($this->task_started)->dayName . ', ' . Carbon::parse($this->task_started)->format('d F Y');
        });
        $grid->column('task_status', __('Status Penugasan'))->using([0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap']);
        $grid->column('employee_fee', __('Ongkos Karyawan'))->display(function () {
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
        $show = new Show(Task::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('employee.employee_name', __('Karyawan'));
        $show->field('order_id', __('Nomor Nota'))->as(function () {
            return "<a href='/admin/orders/" . $this->order_id . "'>" .  $this->order->invoice_number . "</a>";
        });
        $show->field('task_started', __('Tanggal Pengerjaan'))->as(function () {
            return Carbon::parse($this->task_started)->dayName . ', ' . Carbon::parse($this->task_started)->translatedFormat('d F Y');
        });
        $show->field('task_status', __('Status Penugasan'))->using([0 => 'Dalam Pengerjaan', 1 => 'Sudah Siap']);
        $show->field('employee_fee', __('Ongkos Karyawan'))->as(function () {
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
        $form = new Form(new Task());
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
        $form->select('order_id', __('Orderan'))->options($this->selectOrder)->rules('required');
        $form->switch('task_status', __('Status Penugasan'))->states($states)->rules('required');
        $form->date('task_started', __('Tanggal Pengerjaan'))->rules('required|date');
        $form->currency('employee_fee', __('Ongkos Karyawan'))->symbol('Rp.')->rules('required|numeric');
        $form->textarea('note', __('Keterangan'));

        return $form;
    }
}
