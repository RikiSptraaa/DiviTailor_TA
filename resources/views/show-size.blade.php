@php
use App\Models\GroupOrder;
use App\Models\User;
$borongan = GroupOrder::with('group')->get();
$user = User::whereDoesntHave('baju')->get()->pluck('id', 'name')

@endphp
<div class="accordion" id="accordionExample">
    <button id="btnPersonal" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsePersonal"
        aria-expanded="false" aria-controls="collapsePersonal">
        Ukuran Individu
    </button>
    <button id="btnGroup" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseGroup"
        aria-expanded="false" aria-controls="collapseGroup">
        Ukuran Kelompok
    </button>
</div>

<div class="collapse" id="collapseGroup" data-parent="#accordionExample">
    <form action="{{ admin_url('ukuran/show/all') }}" method="GET" id="form-show-size">
        @csrf
        <div class="form-group  ">
            <label for="borongan" class="col-sm-2 control-label">Kode Pesanan / Borongan</label>

            <div class="col-sm-8">
                <select id="borongan" class="form-control select2" style="width: 100%;" name="borongan">
                    @foreach($borongan as $key => $value)
                    <option value="{{ $value->id }}">
                        {{ '('.$value->invoice_number.')-'.$value->order_kind.'-'.$value->group->group_name.'-'.$value->group->institute }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <button class="btn btn-primary" id="cari_data">Cari</button>
    </form>
    <div class="col-sm-12" style="padding-top: 10px">
        <form action="{{ admin_url('uk/baju/multiple-store') }}" id="submit-top-size" method="POST">
            @csrf
            <table id="pelanggan" class="hidden" border="collapse" style="padding: 5px">
                <thead>
                    <tr class="text-center">
                        <td widh="3%">No</td>
                        <td width="13%">Nama Pelanggan</td>
                        <td width="15%">Jenis UK</td>
                        <td width="5%">Kode UK</td>
                        <td width="5%">Panjang Baju <br> (cm)</td>
                        <td width="5%">Lingkar Kerah <br> (cm)</td>
                        <td width="5%">Lingkar Dada <br> (cm)</td>
                        <td width="5%">Lingkar Perut <br> (cm)</td>
                        <td width="5%">Lingkar Pinggul <br> (cm)</td>
                        <td width="5%">Lebar Bahu <br> (cm)</td>
                        <td width="5%">Panjang Lengan Pendek <br> (cm)</td>
                        <td width="5%">Panjang Lengan Panjang <br> (cm)</td>
                        <td width="5%">Lingkar Lengan Atas <br> (cm)</td>
                        <td width="5%">Lingkar Lengan Bawah <br> (cm)</td>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div id="btn-submit" style="display: flex; margin-top: 10px; justify-content: end;">

            </div>
        </form>
    </div>
</div>
<div class="collapse" id="collapsePersonal" data-parent="#accordionExample" style="margin-top: 10px; ">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Buat Ukuran Baju Individu</h3>
            </div>
            <form action="{{ route('admin.alter-store') }}" method="post" class="form-horizontal" id="form-individu">
                @csrf
                <div class="box-body" style="margin-bottom: 10px padding: 5px">
                    <div class="col-md-12">
                        <div class="box-error text-red">
                           
                        </div>
                    </div>

                    <div class="fields-group">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2  control-label">Nama Pelanggan</label>
                                <div class="col-sm-10">
                                    <select name="pelanggan" id="" class="form-control select2" style="width: 100%">
                                        @foreach($user as $key => $value)
                                        <option value="{{ $value }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2  control-label">Jenis Ukuran</label>
                                <div class="col-sm-10">
                                    <select name="jenis_ukuran" id="" class="form-control select2" data-placeholder="Pilih Jenis Ukuran" style="width: 100%">
                                        @foreach(config('const.jenis_ukuran') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2  control-label">Kode Ukuran</label>
                                <div class="col-sm-10">
                                    <select name="kode_ukuran" id="" class="form-control select2" data-placeholder="Pilih Ukuran" style="width: 100%">
                                        @foreach(config('const.kode_ukuran') as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <label for="panjang_baju" class="col-sm-2 control-label">Panjang baju</label>
                            
                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;" type="number" id="panjang_baju" name="panjang_baju" class="form-control panjang_baju initialized" placeholder="Masukan Panjang baju">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_kerah" class="col-sm-2 control-label">Lingkar kerah</label>
                            
                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_kerah" name="lingkar_kerah" class="form-control lingkar_kerah initialized" placeholder="Masukan Lingkar kerah">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_dada" class="col-sm-2 control-label">Lingkar dada</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;" type="number" id="lingkar_dada" name="lingkar_dada" class="form-control lingkar_dada initialized" placeholder="Masukan Lingkar dada">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_perut" class="col-sm-2 control-label">Lingkar perut</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_perut" name="lingkar_perut" class="form-control lingkar_perut initialized" placeholder="Masukan Lingkar perut">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_pinggul" class="col-sm-2 control-label">Lingkar pinggul</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_pinggul" name="lingkar_pinggul" class="form-control lingkar_pinggul initialized" placeholder="Masukan Lingkar pinggul">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lebar_bahu" class="col-sm-2 control-label">Lebar bahu</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lebar_bahu" name="lebar_bahu" class="form-control lebar_bahu initialized" placeholder="Masukan Lebar bahu">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="panjang_lengan_pendek" class="col-sm-2 control-label">Panjang lengan pendek</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="panjang_lengan_pendek" name="panjang_lengan_pendek" class="form-control panjang_lengan_pendek initialized" placeholder="Masukan Panjang lengan pendek">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="panjang_lengan_panjang" class="col-sm-2 control-label">Panjang lengan panjang</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="panjang_lengan_panjang" name="panjang_lengan_panjang" class="form-control panjang_lengan_panjang initialized" placeholder="Masukan Panjang lengan panjang">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_lengan_bawah" class="col-sm-2 control-label">Lingkar lengan bawah</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_lengan_bawah" name="lingkar_lengan_bawah" class="form-control lingkar_lengan_bawah initialized" placeholder="Masukan Lingkar lengan bawah">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_lengan_atas" class="col-sm-2 control-label">Lingkar lengan bawah</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_lengan_atas" name="lingkar_lengan_atas" class="form-control lingkar_lengan_atas initialized" placeholder="Masukan Lingkar lengan bawah">
                                    </div>  
                                </div>

                            </div>
                            
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">

                    <div class="col-md-2">
                    </div>

                    <div class="col-md-8">
                        <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="_previous_" value="http://divitailor.test/admin/uk/baju" class="_previous_">

                <!-- /.box-footer -->
            </form>
        </div>

    </div>
</div>


<script>
    $(document).ready(function () {
        $('#borongan').select2({
            placeholder: 'Pilih Pesanan / Borongan',
            searchInputPlaceholder: 'Cari',
            allowClear: true,
        });

        $('.select2').select2({
            searchInputPlaceholder: 'Cari',
            allowClear: true,
        });

        $('.select2').val(null).trigger('change');

        $('.select2-selection__clear').css

        $('.select2-selection__clear').click(function (e){
            e.preventDefault();
            $('.select2').val(null).trigger('change');
        });

        $('#form-show-size').submit(function (e) {
            e.preventDefault();

            const method = $(this).attr('method');
            const url = $(this).attr('action');
            var form_data = new FormData(this);
            var iter = 1;
            $.ajax({
                method: method,
                url: url,
                data: {
                    'borongan': $('#borongan').val()
                },
                cache: false,
                contentType: false,
                success: function (data) {
                    console.log(data);
                    $('#pelanggan').removeClass('hidden');
                    $('#btn-submit').empty();
                    $('#pelanggan tbody').empty();
                    $.each(data.user, function (key, value) {
                        $('#pelanggan tbody').append(`
                        <tr style="padding: 10px;">
                            <td width='3%' align="center">` + iter++ +
                            `</td>   
                            <td><input type="text" name="user_name[]" class="form-control" readonly style="width: 100%; font-size:small;" value="` +
                            data.data[value].name + `-` + data.data[value]
                            .user_id +
                            `"></td>
                            <td width="5%"><select name="jenis_ukuran[]" id="jenisUk" class="form-control jenis-uk `+ data.data[value].user_id +`" style="width: 100%;">
                            </select></td>
                            <td width="5%"><select name="kode_ukuran[]" id="kodeUk" class="form-control kode-uk `+ data.data[value].user_id +`" style="width: 100%;">
                            </select></td>
                            <td width="5%"><input name="panjang_baju[]" class="form-control" style="width: 100%;" type="number" value="` +
                            data.data[value].panjang_baju +
                            `"></td>
                            <td width="5%"><input name="lingkar_kerah[]" class="form-control" style="width: 100%;"  type="number" value="` + data.data[
                                value].lingkar_kerah +
                            `"></td>
                            <td width="5%"><input name="lingkar_dada[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].lingkar_dada +
                            `"></td>
                            <td width="5%"><input name="lingkar_perut[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].lingkar_perut +
                            `"></td>
                            <td width="5%"><input name="lingkar_pinggul[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].lingkar_pinggul +
                            `"></td>
                            <td width="5%"><input name="lebar_bahu[]" class="form-control" style="width: 100%;"  type="number" value="` + data.data[
                                value].lebar_bahu +
                            `"></td>
                            <td width="5%"><input name="panjang_lengan_pendek[]" class="form-control" style="width: 100%;"  type="number" value="` +
                            data.data[value].panjang_lengan_pendek +
                            `"></td>
                            <td width="5%"><input name="panjang_lengan_panjang[]" class="form-control" style="width: 100%;"  type="number" value="` + data.data[
                                value].panjang_lengan_panjang +
                            `"></td>
                            <td width="5%"><input name="lingkar_lengan_bawah[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].lingkar_lengan_bawah +
                            `"></td>
                            <td width="5%"><input name="lingkar_lengan_atas[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].lingkar_lengan_atas + `"></td>
                        </tr>
                        `);
                        $('.jenis-uk.'+data.data[value].user_id).append(`<option value="null" id='disabled_uk_kind' disabled> Pilih Jenis Ukuran </option>`); 
                        $.each(data.jenisUk, function (key, value_kind_uk){ 
                            $('.jenis-uk.'+data.data[value].user_id).append(`<option value="`+ key +`">`+value_kind_uk+`</option>`);       
                        });        

                        if (data.data[value].jenis_ukuran != undefined) {
                            $('.jenis-uk.'+data.data[value].user_id).val(data.data[value].jenis_ukuran);
                        }else{
                            $('.jenis-uk.'+data.data[value].user_id).val("null");
                        }
                            
                        $('.kode-uk.'+data.data[value].user_id).append(`<option disabled selected> Pilih Kode Ukuran </option>`); 
                        $.each(data.kodeUk, function (key, value_code_uk){ 
                            $('.kode-uk.'+data.data[value].user_id).append(`<option value="`+ key +`">`+value_code_uk+`</option>`); 
                        });

                        if (data.data[value].kode_ukuran != undefined) {
                            $('.kode-uk.'+data.data[value].user_id).val(data.data[value].kode_ukuran);
                        }
                        

                    });
                    $('#btn-submit').append(
                        ` <button type="submit" class="btn btn-primary mt-5">Submit</button>`
                        );

                }
            });

        });

        $('#btnGroup').click(function(){
            $('#collapsePersonal').hide();
            $('#collapseGroup').toggle();
        });
        $('#btnPersonal').click(function(){
            $('#collapseGroup').hide();
            $('#collapsePersonal').toggle();

        });

        $('#form-individu').submit(function (e) {
            e.preventDefault();
            $('.box-error').empty();

            const method_individu = $(this).attr('method');
            const url_individu = $(this).attr('action');
            var form_data_2 = new FormData(this);

            $.ajax({
                method: method_individu,
                url: url_individu,
                data: form_data_2,
                processData: false,
                cache: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        type: 'success',
                        showCloseButton: true,
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Ok',
                        title: 'Berhasil!',
                        text: response.message,
                    }).then((result) => {
                        window.location.href = "{{ route('admin.baju.index') }}"
                    });

                },
                error: function (error) {
                    console.log(error);
                    $.each(error.responseJSON.validators, function (key, value) {
                        $('.box-error').append(
                            `
                            <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>`+ value +`</label><br>
                            `
                        )
                    });
                } 
            });

        });
    });

</script>
