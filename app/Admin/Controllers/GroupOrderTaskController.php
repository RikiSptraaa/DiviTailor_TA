<?php

namespace App\Admin\Controllers;

use Throwable;
use App\Models\User;
use App\Models\Order;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Employee;
use Akaunting\Money\Money;
use App\Models\GroupOrder;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Tab;
use App\Models\GroupOrderTask;
use Illuminate\Support\Carbon;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    protected $selectOrder = [];

    public function __construct(){
        $order = GroupOrder::with('group')->get();

        foreach ($order as $key => $value) {
            $this->selectOrder[$value->id] = $value->group->group_name.'-'.$value->group->institute.' ('.$value->invoice_number.') ('.$value->order_kind.')'; 
        }
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content, Tab $tab)
    {
        $borongan = GroupOrder::all();

        $tab->add('Semua Penugasan Borongan', $this->grid()->render());
        $tab->add('Penugasan Sesuai Borongan', view('admin-task.index', compact('borongan')));

        $content->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->row($tab);
     

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

        $borongan = GroupOrder::all();

            
            // $grid->setView('admin-task.index', compact('borongan'));

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                // $filter->date('task_date', 'Tanggal');
                $filter->equal('group_order_id', 'Nomor Nota')->select($this->selectOrder);
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

        return $grid;
     
        // $grid->column('created_at', __('Tanggal Pengerjaan'));
        // $grid->column('updated_at', __('Updated at'));

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

    protected function showAll(Request $request){
        $id = $request->borongan;

        $groupOrder = GroupOrder::with('task', 'group')->find($request->borongan);
        $coordinator = User::select('name')->find($groupOrder->group->coordinator);
        $handler = Employee::pluck('employee_name', 'id');

        return response()->json([
            'groupOrder' => $groupOrder,
            'coordinator' => $coordinator,
            'groupOrderTask' => $groupOrder->task,
            'handler' => $handler
        ]);
    }

    public function multipleStore(Request $request){
        $validator = Validator::make($request->all(), [
            "handler.*" => 'required',
            "task_date.*" => 'required|date',
            "task_status.*" => 'required',
            "total_unit_asigned.*" => 'required',
            "employee_fee.*" => 'required',
            "employee_fee_total.*" => 'required',
            "note.*" => 'required'
        ], [
            "handler.required" => "Karyawan Wajib Diisi",
            "task_date.*.required" => "Ada Kolom Tanggal Pengerjaan Yang Tidak Terisi",
            "task_status.*.required" => "Ada Kolom Status Penugasan Yang Tidak Terisi",
            "total_unit_asigned.*.required" => "Ada Kolom Jumlah Unit Yang Dikerjakan Yang Tidak Terisi",
            "employee_fee.*.required" => "Ada Kolom Ongkos Karyawan Per Unit Yang Tidak Terisi",
            "employee_fee_total.*.required" => "Ada Kolom Total Ongkos Karyawan Yang Tidak Terisi",
            "note.*.required" => "Ada Kolom Keterangan Yang Tidak Terisi",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Gagal",
                'validators'=> $validator->errors(),
            ], 422);
        };

        DB::beginTransaction();
        try{       
            foreach ($request->handler as $key => $value) {

                GroupOrderTask::updateOrCreate([
                    'group_order_id' =>  $request->group_order_id,
                    'handler_id' => $request->handler[$key],
                    'task_date' =>  $request->task_date[$key],
                    'task_status' =>  $request->task_status[$key],
                    'total_unit_asigned' =>  $request->total_unit_asigned[$key],
                    'employee_fee' =>  $request->employee_fee[$key],
                    'employee_fee_total' =>  $request->employee_fee_total[$key],
                    'note' =>  $request->note[$key],
                ]);
            }
            
            DB::commit();
                
            return response()->json([
                'status' => true,
                'message' => "Data Berhasil Ditambah!",
            ]);             
                            
        }catch (Throwable $th){

            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'error' => $th->getTrace()
            ], 422);

        }
    }
}
