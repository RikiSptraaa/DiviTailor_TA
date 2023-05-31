<div class="box grid-box">

    <form action="{{ admin_url('borongan/tasks/show-all') }}" method="GET" id="form-show-task">
        @csrf
        <div class="form-group">
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
{{-- <div class="col-sm-12" style="padding-top: 10px"> --}}
    <div class="borongan-info" style="margin: 15px; ">
    
    </div>

    <form action="{{ admin_url('borongan/tasks/multiple-store') }}" id="submit-group-task" method="POST">
        @csrf
        <div id="group-task-id">

        </div>
        <table id="task" class="hidden" border="collapse" style="padding: 20px; margin-top: 12px; text-align:center;">
            <thead>
                   <tr>
                        <th style="text-align: center;" width="3%">No</th>
                        <th style="text-align: center;" width="13%">Nama Karyawan</th>
                        <th style="text-align: center;" width="13%">Tanggal Pengerjaan</th>
                        <th style="text-align: center;" width="13%">Status Penugasan</th>
                        <th style="text-align: center;" width="13%">Jumlah Unit Yang Dikerjakan</th>
                        <th style="text-align: center;" width="13%">Ongkos Karyawan Per Unit</th>
                        <th style="text-align: center;" width="13%">Total Ongkos Karyawan</th>
                   </tr>
            </thead>
            <tbody>
                

            </tbody>
        </table>
        <div id="btn-submit" style="display: flex; margin-top: 10px; justify-content: end; margin-bottom: 10px;">

        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    $('#borongan').select2({
        placeholder: 'Pilih Pesanan / Borongan',
        searchInputPlaceholder: 'Cari',
        allowClear: true,
    });


    $('#form-show-task').submit(function (e) {
            e.preventDefault();

            const method = $(this).attr('method');
            const url = $(this).attr('action');
            var form_data = new FormData(this);
            var iter = 1;
            var task_status = [ 'Dalam Pengerjaan', 'Sudah Siap']
            var selected = "selected";
            var curent_iter = 0;
            $.ajax({
                method: method,
                url: url,
                data: {
                    'borongan': $('#borongan').val()
                },
                cache: false,
                contentType: false,
                success: function (data) {
                    curent_iter = Object.keys(data.groupOrderTask).length;
                    $('#group-task-id').append(`<input type="hidden" name="group_order_id" id="group_order_id" value="`+ data.groupOrder.id +`">`)
                    // console.log(data);
                    $('.borongan-info').empty();
                    $('.borongan-info').append(`
                        <h5 style="font-weight: bold;"> Nomor Nota  : `+ data.groupOrder.invoice_number+`</h5>
                        <h5 style="font-weight: bold;"> Group       : `+ data.groupOrder.group.institute + `-` + data.groupOrder.group.group_name +`</h5>
                        <h5 style="font-weight: bold;"> Koordinator  : `+ data.coordinator.name+`</h5>
                        <h5 style="font-weight: bold;"> Jenis Pesanan  : `+ data.groupOrder.order_kind+`</h5>
                        <h5 style="font-weight: bold;"> Total Pelanggan  : `+ data.groupOrder.users_total+` Orang </h5>
                    `);

                    $('#task').removeClass('hidden');
                    $('#btn-submit').empty();
                    $('#task tbody').empty();
                    $.each(data.groupOrderTask, function (key, value) {
                        $('#group-task-id').append(`<input type="hidden" name="group_order_task[]" id="group_order_task" value="`+ value.id +`">`)
                        // $('#group_order_task').val(value.id)
                        $('#task tbody').append(`
                        <tr style="padding: 10px;">
                            <td style="width: 3%;" rowspan="2">`+ iter++  +`</td>
                            <td>
                                    <select disabled name="handler[]" id="task_status" class="select2 handler handler-static `+ value.id +` form-control" style="width: 100%; font-size:small;">
                                    </select>
                            </td>
                            <td><input type="date" name="task_date[]" class=" form-control" style="width: 100%; font-size:small;" value="` +
                            value.task_date+`"></td>
                            <td>
                                <select name="task_status[]" id="task_status" class="select2 task_status `+ value.id +` form-control" style="width: 100%; font-size:small;">
                                    <option value="0">Dalam Pengerjaan</option>
                                    <option value="1">Sudah Siap</option>
                                </select>
                            </td>
                            <td><input type="number" name="total_unit_asigned[]" class=" form-control" style="width: 100%; text-align: center; font-size:small;" value="` +
                            value.total_unit_asigned+`"></td>
                            <td><input type="number" name="employee_fee[]" class=" form-control" style="width: 100%; text-align: center; font-size:small;" value="` +
                            value.employee_fee+`"></td>
                            <td><input type="number" name="employee_fee_total[]" class=" form-control" style="width: 100%; font-size:small;" value="` +
                            value.employee_fee_total+`"></td>
                            </tr>   
                        <tr style="height:100px;">
                            <td style="font-weight: bold;"> Keterangan </td>
                            <td colspan="5" ><textarea rows="10" name="note[]" class=" form-control" style="width: 100%; height:100%; text-align:left">`+value.note+`</textarea>
                            </td>
                        <tr>
                        `);
                        if (value.task_status == 0) {
                            $('.task_status.'+ value.id).val(0);
                        }else{
                            $('.task_status.'+ value.id).val(1);
                        } 

                        // var employee_handler = value.handler_id == 1 ? 1 : 2;
                        

                        $.each(data.handler, function (key, value) {
                            $('.handler').append(`
                                <option value="`+ key +`">`+ value +`</option>
                            `)
                        });

                        $('.handler.'+value.id).val(value.handler_id);


                        
                        
                    });
                    

                    $('#btn-submit').append(
                        ` <button type="button" id="remove-rows" class="btn btn-warning mt-5" >Reset</button>`
                    );

                    $('#btn-submit').append(
                        ` <button type="button" id="add-rows" class="btn btn-primary mt-5" style="margin-left: 5px;">Tambah</button>`
                    );

                
                        
                    $('#btn-submit').append(
                        ` <button type="submit" class="btn btn-success mt-5" style="margin-left: 5px;">Submit</button>`
                    );

                
                    $('#remove-rows').click(function(e){
                        e.preventDefault;
                        $('.new-rows').remove()

                        iter = curent_iter + 1;
                    });

                    $('#add-rows').click(function(e){
                        e.preventDefault;
                        // console.log(new_col_iter);
                        // console.log(iter);

                        $('#task tbody').append(`
                            <tr style="padding: 10px;" class="new-rows">
                                <td style="width: 3%;" rowspan="2">`+ iter++  +`</td>
                                <td>
                                    <select name="handler[]" id="task_status" class="select2 handler form-control" style="width: 100%; font-size:small;">
                                    </select>
                                </td>
                                <td><input type="date" name="task_date[]" class=" form-control" style="width: 100%; font-size:small;"></td>
                                <td>
                                    <select name="task_status[]" id="task_status" class="select2 task_status form-control" style="width: 100%; font-size:small;">
                                        <option value="0">Dalam Pengerjaan</option>
                                        <option value="1">Sudah Siap</option>
                                    </select>
                                </td>
                                <td><input type="number" name="total_unit_asigned[]" class=" form-control" style="width: 100%; text-align: center; font-size:small;"></td>
                                <td><input type="number" name="employee_fee[]" class=" form-control" style="width: 100%; text-align: center; font-size:small;"></td>
                                <td><input type="number" name="employee_fee_total[]" class=" form-control" style="width: 100%; font-size:small;"></td>
                            </tr>   
                            <tr style="height:100px;"  class="new-rows">
                                <td style="font-weight: bold;"> Keterangan </td>
                                <td colspan="5" ><textarea rows="10" name="note[]" class=" form-control" style="width: 100%; height:100%; text-align:left"></textarea>
                                </td>
                            <tr>
                        `)

                        $.each(data.handler, function (key, value) {
                            $('.handler').append(`
                                <option value="`+ key +`">`+ value +`</option>
                            `)
                        });
                    });

                }
            });

        });
        


    $('#submit-group-task').submit(function (e) {
            e.preventDefault();
            $('.handler-static').removeAttr('disabled');
            const method = $(this).attr('method');
            const url = $(this).attr('action');
            const form_data = $(this).serializeArray();

            $.ajax({
                method: method,
                url: url,
                data: form_data,
                cache: false,
                success: function (data) {
                    $('.handler-static').prop('disabled', 'disabled');
                   
                   // call the swal() function
                   swal({
                     title: 'Sukses',
                     text: data.message,
                     type: 'success',
                   }).then((result) => {
                        location.reload();
                   });

                },
                error: function (error){
                    $('.handler-static').prop('disabled', 'disabled');
                    
                    var data = error.responseJSON.validators;

                    var html = "";

                    $.each(data, function(key, value)
                    {
                        html += "<li>"+ value +"</li>"
                    });


                    console.log(html);

                    
                    swal({
                        title: 'Kesalahan!',
                        type: 'warning',
                        html: `<p style="font-weight: bold"> Terdapat Beberapa Kolom Yang Tidak Terisi </P>
                        <ul style="color: red; text-align: left;">`+html+`</ul>
                        `
                    
                    });


                }
            });


    });
    
    $('.select2').select2({
        searchInputPlaceholder: 'Cari',
        allowClear: true,
    });

    // $('.select2').val(null).trigger('change');

    $('.select2-selection__clear').click(function (e){
        e.preventDefault();
        $('.select2').val(null).trigger('change');
    });

});
</script>