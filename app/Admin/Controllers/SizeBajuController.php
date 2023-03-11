<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\SizeBaju;
use App\Models\GroupOrder;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;
use Encore\Admin\Controllers\HasResourceActions;


class SizeBajuController extends Controller
{
    use HasResourceActions;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ukuran Baju';

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

    protected function grid()
    {
        $grid = new Grid(new SizeBaju());
        $grid->disableExport();

        // $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            $filter->like('user.name', 'Nama Pelanggan');
        });
        $grid->column('id', __('ID'))->sortable();
        $grid->column('user.name', __('Nama Pelanggan'));
        // $grid->column('panjang_baju', __('Panjang baju'))->display(function () {
        //     return $this->panjang_baju . ' cm';
        // });
        // $grid->column('lingkar_kerah', __('Lingkar kerah'))->display(function () {
        //     return $this->lingkar_kerah . ' cm';
        // });
        // $grid->column('lingkar_dada', __('Lingkar dada'))->display(function () {
        //     return $this->lingkar_dada . ' cm';
        // });
        // $grid->column('lingkar_perut', __('Lingkar perut'))->display(function () {
        //     return $this->lingkar_perut . ' cm';
        // });
        // $grid->column('lingkar_pinggul', __('Lingkar pinggul'))->display(function () {
        //     return $this->lingkar_pinggul . ' cm';
        // });
        // $grid->column('lebar_bahu', __('Lebar bahu'))->display(function () {
        //     return $this->lebar_bahu . ' cm';
        // });
        // $grid->column('panjang_lengan_pendek', __('Panjang lengan pendek'))->display(function () {
        //     return $this->panjang_lengan_pendek . ' cm';
        // });
        // $grid->column('panjang_lengan_panjang', __('Panjang lengan panjang'))->display(function () {
        //     return $this->panjang_lengan_panjang . ' cm';
        // });
        // $grid->column('lingkar_lengan_bawah', __('Lingkar lengan bawah'))->display(function () {
        //     return $this->lingkar_lengan_bawah . ' cm';
        // });
        // $grid->column('lingkar_lengan_atas', __('Lingkar lengan atas'))->display(function () {
        //     return $this->lingkar_lengan_atas . ' cm';
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
        $show = new Show(SizeBaju::findOrFail($id));



        $show->field('id', __('Id'));
        $show->field('user.name', __('Nama Pelanggan'));
        $show->field('panjang_baju', __('Panjang baju'))->as(function () {
            return "{$this->panjang_baju} cm";
        });
        $show->field('lingkar_kerah', __('Lingkar kerah'))->as(function () {
            return "{$this->lingkar_kerah} cm";
        });
        $show->field('lingkar_dada', __('Lingkar dada'))->as(function () {
            return "{$this->lingkar_dada} cm";
        });
        $show->field('lingkar_perut', __('Lingkar perut'))->as(function () {
            return "{$this->lingkar_perut} cm";
        });
        $show->field('lingkar_pinggul', __('Lingkar pinggul'))->as(function () {
            return "{$this->lingkar_pinggul} cm";
        });
        $show->field('lebar_bahu', __('Lebar bahu'))->as(function () {
            return "{$this->lebar_bahu} cm";
        });
        $show->field('panjang_lengan_pendek', __('Panjang lengan pendek'))->as(function () {
            return "{$this->panjang_lengan_pendek} cm";
        });
        $show->field('panjang_lengan_panjang', __('Panjang lengan panjang'))->as(function () {
            return "{$this->panjang_lengan_panjang} cm";
        });
        $show->field('lingkar_lengan_bawah', __('Lingkar lengan bawah'))->as(function () {
            return "{$this->lingkar_lengan_bawah} cm";
        });
        $show->field('lingkar_lengan_atas', __('Lingkar lengan atas'))->as(function () {
            return "{$this->lingkar_lengan_atas} cm";
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
        $form = new Form(new SizeBaju());
        if ($edit) {
            $form->display('user.name', __('Nama Pelanggan'));
            $form->number('panjang_baju', __('Panjang baju'))->rules('required|numeric');
            $form->number('lingkar_kerah', __('Lingkar kerah'))->rules('required|numeric');
            $form->number('lingkar_dada', __('Lingkar dada'))->rules('required|numeric');
            $form->number('lingkar_perut', __('Lingkar perut'))->rules('required|numeric');
            $form->number('lingkar_pinggul', __('Lingkar pinggul'))->rules('required|numeric');
            $form->number('lebar_bahu', __('Lebar bahu'))->rules('required|numeric');
            $form->number('panjang_lengan_pendek', __('Panjang lengan pendek'))->rules('required|numeric');
            $form->number('panjang_lengan_panjang', __('Panjang lengan panjang'))->rules('required|numeric');
            $form->number('lingkar_lengan_bawah', __('Lingkar lengan bawah'))->rules('required|numeric');
            $form->number('lingkar_lengan_atas', __('Lingkar lengan atas'))->rules('required|numeric');
    
            return $form;
        } else {
            return $form->setView('show-size');
        }
 
    }

    public function showAll (Request $request){
        $groupOrder = GroupOrder::with('user')->find($request->borongan);
        $groupOrderUserId = $groupOrder->user->pluck('id', 'name')->toArray();
        $customer = User::with('baju')->whereIn('id',$groupOrderUserId)->get()->toArray();
        $mappingSize =[];

        foreach($customer as $key => $value){
            if (!is_null($value['baju'])) {
                    $mappingSize[$value['id']] = $value['baju'];
                    $mappingSize[$value['id']]['name'] = $value['name'];
            }else{
                $mappingSize[$value['id']] = [
                    'name' => $value['name'],
                    'user_id' => $value['id'],
                    "panjang_baju" => 0,
                    "lingkar_kerah" => 0,
                    "lingkar_dada" => 0,
                    "lingkar_perut" => 0,
                    "lingkar_pinggul" => 0,
                    "lebar_bahu" => 0,
                    "panjang_lengan_pendek" => 0,
                    "panjang_lengan_panjang" => 0,
                    "lingkar_lengan_bawah" => 0,
                    "lingkar_lengan_atas" => 0,
                ];
            }
        }


        return response()->json([
            'status' => true,
            'data' => $mappingSize,
            'user' => $groupOrderUserId,
        ]);
    }

    public function multipleStore(Request $request)
    {
        $userId = [];
        foreach ($request->user_name as $key => $value) {
            $userId[$key] = explode('-',$value)[1];
        }

        foreach($userId as $key => $value){
            SizeBaju::updateOrCreate([
                'user_id' => $value,
            ],[
                'user_id' => $value,
                'panjang_baju' => $request->panjang_baju[$key],
                'lingkar_kerah' => $request->lingkar_kerah[$key],
                'lingkar_dada' => $request->lingkar_dada[$key],
                'lingkar_perut' => $request->lingkar_perut[$key],
                'lingkar_pinggul' => $request->lingkar_pinggul[$key],
                'lebar_bahu' => $request->lebar_bahu[$key],
                'panjang_lengan_pendek' => $request->panjang_lengan_pendek[$key],
                'panjang_lengan_panjang' => $request->panjang_lengan_panjang[$key],
                'lingkar_lengan_bawah' => $request->lingkar_lengan_bawah[$key],
                'lingkar_lengan_atas' => $request->lingkar_lengan_atas[$key],
            ]);
        }
        
        $success = new MessageBag([
            'title'   => 'Berhasil',
            'message' => 'Data Berhasil Disimpan',
        ]);

        return redirect(admin_url('uk/baju'))->with(compact('success'));
      
    }
}
