<?php

namespace App\Admin\Controllers;

use Throwable;
use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\GroupOrder;
use App\Models\SizeCelana;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Controllers\HasResourceActions;

class SizeCelanaController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ukuran Celana';

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
        // $content->body($this->box('pdf.pesanan'));

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
    public function box($view, $collapse = false)
    {
        $box = new Box('BOX');
        if ($collapse == true) {
            $box->collapsable();
        }
        $box->style('danger');
        $box->content(view($view));
        return $box;
    }

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
            ->body($this->form($edit = true)->edit($id));
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
        // return redirect('/admin/uk/baju');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SizeCelana());
        $grid->filter(function ($filter) {
            $filter->like('user.name', 'Nama Pelanggan');
        });

        $grid->column('id', __('Id'));
        $grid->column('user.name', __('Nama Pelanggan'));
        $grid->disableExport();
        // $grid->column('lingkar_pinggang', __('Lingkar pinggang'))->display(function () {
        //     return $this->lingkar_pinggang . ' cm';
        // });
        // $grid->column('lingkar_pinggul', __('Lingkar pinggul'))->display(function () {
        //     return $this->lingkar_pinggul . ' cm';
        // });
        // $grid->column('panjang_celana', __('Panjang celana'))->display(function () {
        //     return $this->panjang_celana . ' cm';
        // });
        // $grid->column('panjang_pesak', __('Panjang pesak'))->display(function () {
        //     return $this->panjang_pesak . ' cm';
        // });
        // $grid->column('lingkar_bawah', __('Lingkar bawah'))->display(function () {
        //     return $this->lingkar_bawah . ' cm';
        // });
        // $grid->column('lingkar_paha', __('Lingkar paha'))->display(function () {
        //     return $this->lingkar_paha . ' cm';
        // });
        // $grid->column('lingkar_lutut', __('Lingkar lutut'))->display(function () {
        //     return $this->lingkar_lutut . ' cm';
        // });

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
        $show = new Show(SizeCelana::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('jenis_ukuran', __('Jenis Ukuran'))->using(config('const.jenis_ukuran'));
        $show->field('kode_ukuran', __('Kode Ukuran'))->using(config('const.kode_ukuran'));
        $show->field('lingkar_pinggang', __('Lingkar pinggang'))->as(function () {
            return "{$this->lingkar_pinggang} cm";
        });
        $show->field('lingkar_pinggul', __('Lingkar pinggul'))->as(function () {
            return "{$this->lingkar_pinggul} cm";
        });
        $show->field('panjang_celana', __('Panjang celana'))->as(function () {
            return "{$this->panjang_celana} cm";
        });
        $show->field('panjang_pesak', __('Panjang pesak'))->as(function () {
            return "{$this->panjang_pesak} cm";
        });
        $show->field('lingkar_bawah', __('Lingkar bawah'))->as(function () {
            return "{$this->lingkar_bawah} cm";
        });
        $show->field('lingkar_paha', __('Lingkar paha'))->as(function () {
            return "{$this->lingkar_paha} cm";
        });
        $show->field('lingkar_lutut', __('Lingkar lutut'))->as(function () {
            return "{$this->lingkar_lutut} cm";
        });
        // $show->field('created_at', __('Created at'));
        // $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($edit = false)
    {
        $form = new Form(new SizeCelana());
        if ($edit) {
            $form->display('user.name', __('Nama Pelanggan'));
            $form->select('jenis_ukuran', __('Jenis Ukuran'))->options(config('const.jenis_ukuran'))->rules('numeric');
            $form->select('kode_ukuran', __('Kode Ukuran'))->options(config('const.kode_ukuran'))->rules('numeric');
            $form->number('lingkar_pinggang', __('Lingkar pinggang'))->rules('numeric');
            $form->number('lingkar_pinggul', __('Lingkar pinggul'))->rules('numeric');
            $form->number('panjang_celana', __('Panjang celana'))->rules('numeric');
            $form->number('panjang_pesak', __('Panjang pesak'))->rules('numeric');
            $form->number('lingkar_bawah', __('Lingkar bawah'))->rules('numeric');
            $form->number('lingkar_paha', __('Lingkar paha'))->rules('numeric');
            $form->number('lingkar_lutut', __('Lingkar lutut'))->rules('numeric');
        } else {
            // $form->select('user_id', __('Nama Pelanggan'))->options(User::all()->pluck('name', 'id'))->rules('unique:size_celanas,user_id|required');
            $form->setView('size.form-bottom-size');
        }
      

        return $form;
    }

    public function showAll (Request $request){
        $groupOrder = GroupOrder::with('user')->find($request->borongan);
        $groupOrderUserId = $groupOrder->user->pluck('id', 'name')->toArray();
        $customer = User::with('celana')->whereIn('id',$groupOrderUserId)->get()->toArray();
        $mappingSize =[];

        foreach($customer as $key => $value){
            if (!is_null($value['celana'])) {
                    $mappingSize[$value['id']] = $value['celana'];
                    $mappingSize[$value['id']]['name'] = $value['name'];
            }else{
                $mappingSize[$value['id']] = [
                    'name' => $value['name'],
                    'user_id' => $value['id'],
                    "lingkar_pinggang" => 0,
                    "lingkar_pinggul" => 0,
                    "panjang_celana" => 0,
                    "panjang_pesak" => 0,
                    "lingkar_bawah" => 0,
                    "lingkar_paha" => 0,
                    "lingkar_lutut" => 0,
                    "jenis_ukuran" => null,
                    "kode_ukuran" => null,
                ];
            }
        }


        return response()->json([
            'status' => true,
            'data' => $mappingSize,
            'user' => $groupOrderUserId,
            'jenisUk' => config('const.jenis_ukuran'),
            'kodeUk' => config('const.kode_ukuran'),
        ]);
    }

    public function multipleStore(Request $request)
    {
        $userId = [];
        foreach ($request->user_name as $key => $value) {
            $userId[$key] = explode('-',$value)[1];
        }

        foreach($userId as $key => $value){
              $data = [ 
                'user_id' => $value,
                'lingkar_pinggang' => $request->lingkar_pinggang[$key],
                'lingkar_pinggul' => $request->lingkar_pinggul[$key],
                'panjang_celana' => $request->panjang_celana[$key],
                'panjang_pesak' => $request->panjang_pesak[$key],
                'lingkar_bawah' => $request->lingkar_bawah[$key],
                'lingkar_paha' => $request->lingkar_paha[$key],
                'lingkar_lutut' => $request->lingkar_lutut[$key],
            ];
            if(isset($request->jenis_ukuran[$key]) && !is_null($request->jenis_ukuran[$key])){
                $data['jenis_ukuran'] = $request->jenis_ukuran[$key];
            }
            if(isset($request->kode_ukuran[$key]) && !is_null($request->kode_ukuran[$key])){
                $data['kode_ukuran'] = $request->kode_ukuran[$key];
            }
            SizeCelana::updateOrCreate([
                'user_id' => $value,
            ],$data);
        }
        
        $success = new MessageBag([
            'title'   => 'Berhasil',
            'message' => 'Data Berhasil Disimpan',
        ]);

        return redirect(admin_url('uk/celana'))->with(compact('success'));
      
    }

    public function alterStore(Request $request){
        $validator = Validator::make($request->all(), [
            'pelanggan' => ['required', 'numeric'],
            'jenis_ukuran' => [ 'numeric', 'nullable'],
            'kode_ukuran' => ['string', 'nullable' , 'max:5'],
            'lingkar_pinggang' => ['numeric', 'nullable'],
            'lingkar_pinggul' => ['numeric', 'nullable'],
            'panjang_celana' => ['numeric', 'nullable'],
            'panjang_pesak' => ['numeric', 'nullable'],
            'lingkar_bawah' => ['numeric', 'nullable'],
            'lingkar_paha' => ['numeric', 'nullable'],
            'lingkar_lutut' => ['numeric', 'nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => " Ukuran Gagal Dibuat!",
                'validators'=> $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $user = $request->pelanggan;
            $request = $request->merge(['user_id' => $user]);
            SizeCelana::create(
                $request->except(['_token', '_previous_', 'pelanggan']));
            
            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => "Data Berhasil Ditambah!",
            ]);
        } catch (Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->message,
            ], 422);

        }
    }
}
