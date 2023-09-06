<form action={{ admin_url("/report/generate") }} method="POST" id="generate-report">
<div class="row">
    <div class="col-sm-8 align-content-center" style="display: flex;">
        @csrf
        <input style="display: inline; width: 50%; margin-right:2px;" type="text" id="order_date" name="order_date" class="form-control" placeholder="Masukan Tanggal">
        <button type="submit" class="btn btn-primary" id="cari_data">Cari</button>
    </div>
</div>
</form>

<div class="row row-report" style="display: none;">
    <div class="col-sm-6">
        <h3 class="report-title-income"></h3>
        <table class="pendapatan" border="collapse" style="padding: 5px; width: 100%;">
  
        </table>
    </div>
    <div class="col-sm-6">
        <h3 class="report-title-expense"></h3>
        <table class="pengeluaran" border="collapse" style="padding: 5px; width: 100%;">
  
        </table>
    </div>
</div>
<div class="row row-report" style="display: none;">
    <div class="col-sm-12">
        <h3 class="report-total"></h3>
        <table class="total" border="collapse" style="padding: 5px; width: 100%;">
  
        </table>
    </div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$( document ).ready(function() {
    $('#order_date').daterangepicker();

    $('#generate-report').submit(function(e){
        e.preventDefault();

        var method = $(this).attr("method");
        var action = $(this).attr('action');
        formData = $(this).serializeArray();
        
        $.ajax({
            method : method,
            url : action,
            data : formData,
            success : function(res){
                console.log(res);
                Swal.fire({
                    icon: 'success',
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: true,
                    confirmButtonText: 'Ok',
                    title: 'Berhasil!',
                    text: res.message,
                })
                $('.pendapatan').html('');
                $('.pengeluaran').html('');

                var totalPendapatan = 0;
                var totalPengeluaran = 0;

                if (res.data.pendapatan !== undefined) {
                    
                    $('.pendapatan').append(`<tr> <td style="padding: 10px; font-size: 20px" colspan="4"> <strong> Pesanan / Pesanan Grup </strong> </td> </tr>`)
                    $('.pendapatan').append(`
                        <tr>
                            <td style="padding: 10px;"> <strong> Nomor Pesanan </strong> </td>
                            <td style="padding: 10px;"> <strong> Pemesan </strong> </td>
                            <td style="padding: 10px;"> <strong> Tanggal </strong> </td>
                            <td style="padding: 10px;"> <strong> Nominal </strong> </td>
                        </tr>
                    `)
                    res.data.pendapatan.forEach(function(item) {
                        $('.pendapatan').append(`
                            <tr>
                                <td style="padding: 10px;">`+item.invoice_number+`</td>
                                <td style="padding: 10px;">`+item.orderer+`</td>
                                <td style="padding: 10px;">`+item.date+`</td>
                                <td style="padding: 10px;">`+item.price+`</td>
                            </tr>
                        `)

                    })

                    $('.pendapatan').append(`
                        <tr>
                            <td style="padding: 10px;" colspan="3"> <strong> Total </strong> </td>
                            <td style="padding: 10px;">`+ res.incomeTotal +`</td>
                        </tr>
                    `)

                }else{
                    $('.pendapatan').append(`<tr> <td style="padding: 10px; text-align:center;" colspan="4"> <strong> Tidak Ada Pendapatan Pada Rentang Tanggal Tersebut </strong> </td> </tr>`)

                }

                if (res.data.pengeluaran !== undefined) {

                    $('.pengeluaran').append(`<tr> <td style="padding: 10px; font-size: 20px" colspan="4"> <strong> Pembayaran Gaji </strong> </td> </tr>`)
                    $('.pengeluaran').append(`
                        <tr>
                            <td style="padding: 10px;"> <strong> Nomor Pesanan </strong> </td>
                            <td style="padding: 10px;"> <strong> Karyawan </strong> </td>
                            <td style="padding: 10px;"> <strong> Tanggal </strong> </td>
                            <td style="padding: 10px;"> <strong> Nominal Bayar Gaji </strong> </td>
                        </tr>
                    `)
                    res.data.pengeluaran.forEach(function(item) {
                        $('.pengeluaran').append(`
                            <tr>
                                <td style="padding: 10px;">`+item.invoice_number+`</td>
                                <td style="padding: 10px;">`+item.employee+`</td>
                                <td style="padding: 10px;">`+item.date+`</td>
                                <td style="padding: 10px;">`+item.price+`</td>
                            </tr>
                        `)

                    })

                    $('.pengeluaran').append(`
                        <tr>
                            <td style="padding: 10px;" colspan="3"> <strong> Total </strong> </td>
                            <td style="padding: 10px;">`+ res.expenseTotal +`</td>
                        </tr>
                    `)

                }else{
                    $('.pengeluaran').append(`<tr> <td style="padding: 10px; text-align:center;" colspan="4"> <strong> Tidak Ada Pengeluaran Pada Rentang Tanggal Tersebut </strong> </td> </tr>`)

                }

                $('.total').html('');
                $('.total').append(`<tr> <td style="padding: 10px; font-size: 20px" colspan="4"> <strong> Total:  </strong>`+ res.profitLoss + `</td> </tr>`)

                $('.report-title-income').html('Pendapatan');
                $('.report-title-expense').html('Pengeluaran');
                $('.row-report').fadeIn();
            },
            error: function(err){
                Swal.fire({
                    icon: 'warning',
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: true,
                    confirmButtonText: 'Ok',
                    title: 'Gagal!',
                    text: err.responseJSON.message,
                })
            }
        });

        

    })

})
</script>