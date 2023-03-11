@php
    use App\Models\GroupOrder;
    $borongan = GroupOrder::with('group')->get();

@endphp
<form action="{{ admin_url('ukuran/show/all') }}" method="GET" id="form-show-size">
    @csrf
    <div class="form-group  ">
        <label for="borongan" class="col-sm-2 control-label">Kode Pesanan / Borongan</label>

        <div class="col-sm-8">
            <select id="borongan" class="form-control select2" style="width: 100%;" name="borongan">
                @foreach($borongan as $key => $value)
                <option value="{{ $value->id }}">{{ '('.$value->invoice_number.')-'.$value->order_kind.'-'.$value->group->group_name.'-'.$value->group->institute }}</option>  
                @endforeach
            </select>
        </div>
    </div>

    <button class="btn btn-primary" id="cari_data">Cari</button>
</form>
<div class="col-sm-12">
    <form action="{{ admin_url('uk/baju/multiple-store') }}" id="submit-top-size" method="POST">
        @csrf
        <table id="pelanggan" class="hidden" border="collapse" style="padding: 5px">
            <thead>
                <tr class="text-center">
                    <td widh="5%">No</td>
                    <td width="20%">Nama Pelanggan <br> (cm)</td>
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
<script>
    $(document).ready(function () {
        $('#borongan').select2({
            placeholder: 'Pilih Pesanan / Borongan',
            searchInputPlaceholder: 'Cari',
            allowClear: true,
        });
        $('#form-show-size').submit(function(e){
            e.preventDefault();

            const method = $(this).attr('method');
            const url = $(this).attr('action');
            var form_data = new FormData(this);
            var iter = 1;
            $.ajax({
                method : method,
                url : url,
                data : {'borongan' : $('#borongan').val()},
                cache: false,
                contentType: false,
                success : function(data){
                    console.log(data);
                    $('#pelanggan').removeClass('hidden');
                    $('#btn-submit').empty();
                    $('#pelanggan tbody').empty();
                    $.each( data.user, function( key, value ) {
                        $('#pelanggan tbody').append(`
                        <tr style="padding: 10px;">
                            <td width='5%' align="center">`+ iter++ +`</td>   
                            <td><input type="text" name="user_name[]" class="form-control" readonly style="width: 100%;" value="`+data.data[value].name+`-`+data.data[value].user_id+`"></td>
                            <td width="5%"><input name="panjang_baju[]" class="form-control" style="width: 100%;" type="number" value="`+data.data[value].panjang_baju+`"></td>
                            <td width="5%"><input name="lingkar_kerah[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lingkar_kerah+`"></td>
                            <td width="5%"><input name="lingkar_dada[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lingkar_dada+`"></td>
                            <td width="5%"><input name="lingkar_perut[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lingkar_perut+`"></td>
                            <td width="5%"><input name="lingkar_pinggul[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lingkar_pinggul+`"></td>
                            <td width="5%"><input name="lebar_bahu[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lebar_bahu+`"></td>
                            <td width="5%"><input name="panjang_lengan_pendek[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].panjang_lengan_pendek+`"></td>
                            <td width="5%"><input name="panjang_lengan_panjang[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].panjang_lengan_panjang+`"></td>
                            <td width="5%"><input name="lingkar_lengan_bawah[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lingkar_lengan_bawah+`"></td>
                            <td width="5%"><input name="lingkar_lengan_atas[]" class="form-control" style="width: 100%;"  type="number" value="`+data.data[value].lingkar_lengan_atas+`"></td>
                        </tr>
                        `);
                        
                    });
                    $('#btn-submit').append(` <button type="submit" class="btn btn-primary mt-5">Submit</button>`);
                 
                 
                }
            });
       
        });
    });

</script>
