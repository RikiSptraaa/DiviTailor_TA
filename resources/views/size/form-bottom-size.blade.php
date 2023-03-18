@php
use App\Models\GroupOrder;
use App\Models\User;
$borongan = GroupOrder::with('group')->get();
$user = User::whereDoesntHave('celana')->get()->pluck('id', 'name');

@endphp

<div class="accordion" id="accordionExample">
    <button id="btnPersonal" class="btn btn-primary" type="button" data-toggle="collapse"
        data-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
        Ukuran Individu
    </button>
    <button id="btnGroup" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseGroup"
        aria-expanded="false" aria-controls="collapseGroup">
        Ukuran Kelompok
    </button>
</div>
<div class="collapse" id="collapseGroup" data-parent="#accordionExample">
    <form action="{{ route('admin.show-all') }}" method="GET" id="form-show-size">
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
        <form action="{{route('admin.celana.multiple-store') }}" id="submit-top-size" method="POST">
            @csrf
            <table id="pelanggan" class="hidden" border="collapse" style="padding: 5px">
                <thead>
                    <tr class="text-center">
                        <td widh="5%">No</td>
                        <td width="20%">Nama Pelanggan <br> (cm)</td>
                        <td width="5%">Lingkar Pinggang <br> (cm)</td>
                        <td width="5%">Lingkar Pinggul <br> (cm)</td>
                        <td width="5%">Panjang Celana <br> (cm)</td>
                        <td width="5%">Panjang Pesak <br> (cm)</td>
                        <td width="5%">Lingkar Bawah <br> (cm)</td>
                        <td width="5%">Lingkar Paha <br> (cm)</td>
                        <td width="5%">Lingkar Lutut <br> (cm)</td>
                   
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
                <h3 class="box-title">Buat Ukuran Celana Individu</h3>
            </div>
            <form action="{{ route('admin.celana.alter-store') }}" method="post" class="form-horizontal" id="form-individu">
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

                                <label for="lingkar_pinggang" class="col-sm-2 control-label">Lingkar Pinggang</label>
                            
                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;" type="number" id="lingkar_pinggang" name="lingkar_pinggang" class="form-control lingkar_pinggang initialized" placeholder="Masukan Lingkar pinggang">
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

                                <label for="panjang_celana" class="col-sm-2 control-label">Panjang celana</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;" type="number" id="panjang_celana" name="panjang_celana" class="form-control panjang_celana initialized" placeholder="Masukan Panjang celana">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="panjang_pesak" class="col-sm-2 control-label">Panjang pesak</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="panjang_pesak" name="panjang_pesak" class="form-control panjang_pesak initialized" placeholder="Masukan Panjang pesak">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_bawah" class="col-sm-2 control-label">Lingkar bawah</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_bawah" name="lingkar_bawah" class="form-control lingkar_bawah initialized" placeholder="Masukan Lingkar bawah">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_paha" class="col-sm-2 control-label">Lingkar paha</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_paha" name="lingkar_paha" class="form-control lingkar_paha initialized" placeholder="Masukan Lingkar paha">
                                    </div>  
                                </div>

                            </div>
                            <div class="form-group">

                                <label for="lingkar_lutut" class="col-sm-2 control-label">Lingkar lutut</label>

                                <div class="col-sm-8">
                                    <div class="input-group" style="width: 100%">
                                        <input style="width: 100%; text-align: center;"type="number" id="lingkar_lutut" name="lingkar_lutut" class="form-control lingkar_lutut initialized" placeholder="Masukan Lingkar lutut">
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

                <input type="hidden" name="_previous_" value="http://divitailor.test/admin/uk/celana" class="_previous_">

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
                            <td width='5%' align="center">` + iter++ +
                            `</td>   
                            <td><input type="text" name="user_name[]" class="form-control" readonly style="width: 100%;" value="` +
                            data.data[value].name + `-` + data.data[value]
                            .user_id +
                            `"></td>
                            <td width="5%"><input name="lingkar_pinggang[]" class="form-control" style="width: 100%;" type="number" value="` +
                            data.data[value].lingkar_pinggang +
                            `"></td>
                            <td width="5%"><input name="lingkar_pinggul[]" class="form-control" style="width: 100%;"  type="number" value="` + data.data[
                                value].lingkar_pinggul +
                            `"></td>
                            <td width="5%"><input name="panjang_celana[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].panjang_celana +
                            `"></td>
                            <td width="5%"><input name="panjang_pesak[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].panjang_pesak +
                            `"></td>
                            <td width="5%"><input name="lingkar_bawah[]" class="form-control" style="width: 100%;"  type="number" value="` + data
                            .data[value].lingkar_bawah +
                            `"></td>
                            <td width="5%"><input name="lingkar_paha[]" class="form-control" style="width: 100%;"  type="number" value="` + data.data[
                                value].lingkar_paha +
                            `"></td>
                            <td width="5%"><input name="lingkar_lutut[]" class="form-control" style="width: 100%;"  type="number" value="` +
                            data.data[value].lingkar_lutut +
                            `"></td>
                        </tr>
                        `);

                    });
                    $('#btn-submit').append(
                        ` <button type="submit" class="btn btn-primary mt-5">Submit</button>`
                        );

                }
            });

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
                        window.location.href = "{{ route('admin.celana.index') }}"
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
