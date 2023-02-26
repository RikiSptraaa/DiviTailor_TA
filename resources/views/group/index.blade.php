@php
$no =1;
@endphp
<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-10">
        <div class="flex flex-col w-full">
            <div class="card w-full bg-base-100 shadow-xl image-full mt-2" data-aos="fade-up" data-aos-duration="1000"
                data-aos-once="true">
                <div class="card-body">
                    <h2 class="card-title">Butuh Grup Untuk Membuat Pesanan Borongan?</h2>
                    <p>Jadikan Pseanan Borongan anda lebih mudah untuk dikelola</p>
                    <div class="card-actions justify-end">
                        <label for="modal-create-group" class="btn">Buat Grup</a>
                    </div>
                </div>
            </div>
            <input type="checkbox" class="modal-toggle" id="modal-create-group" />
            <div class="modal">
                <div class="modal-box">
                    <label for="modal-create-group" class="btn btn-xs absolute left-2 top-2">✕</label>
                    <h3 class="font-bold text-lg my-4">Buat Grup</h3>
                    <form id="create-group-form" action="{{ route('group.store') }}" method="post">
                        @csrf
                        <div>
                            <x-input-label for="group_name" :value="__('Nama Grup')" />
                            <input type="text" name="nama_grup" id="group_name"
                                value="{{ old('nama_grup') }}"
                                class="input input-sm input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-group-name">
                            </ul>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="group_phone_number" :value="__('Nomor Telepon Grup')" />
                            <input type="text" name="nomor_telepon_grup" id="group_phone_number" value="{{ old('nomor_telepon_grup')}}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-group-phone-no">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="institute" :value="__('Asal Instansi')" />
                            <input type="text" name="instansi" id="institute" value="{{ old('instansi')}}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-group-institute">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="group-email" :value="__('E-Mail Grup')" />
                            <input type="text" name="email_grup" id="group-email" value="{{ old('email_grup')}}"
                                class="input h-[35px] input-bordered w-full focus:ring-black focus:border-none text-xs" />
                            <ul class='text-sm text-red-600 space-y-1' id="error-group-email">
                            </ul>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="group-address" :value="__('Alamat')" />

                            <textarea
                                class="textarea textarea-bordered w-full focus:ring-gray-600 focus:border-none text-xs"
                                name="alamat_grup" id="group-address">{{ old('alamat_grup') }}</textarea>

                            <ul class='text-sm text-red-600 space-y-1' id="error-group-address">
                            </ul>
                        </div>
             
                        <div class="modal-action">
                            <button  class="btn btn-sm"
                                id="btn-save">Buat Grup</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="divider"></div>
            <p class="text-lg font-semibold">Grup Yang Dimiliki</p>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full border-none" id="group-tables">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th></th>
                            <th>Kode Grup</th>
                            <th>Nama Grup</th>
                            <th>Nomor Telepon Grup</th>
                            <th>Instansi</th>
                            <th>E-Mail</th>
                            <th>Alamat Grup</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group as $key => $value)
                        <tr>
                            <th>{{ $no++ }}</th>
                            <th>{{ $value->group_code }}</th>
                            <td>{{ $value->group_name }}</td>
                            <td>{{ $value->group_phone_number }}</td>
                            <td>{{ $value->institute }}</td>
                            <td>{{ $value->email }}</td>
                            <td>{{ $value->group_address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @section('script')
    <script>
    $(document).ready(function () {
        $('#group-tables').DataTable({
            "lengthChange": false,
            "ordering": false,
            "language": {
                "search": 'Cari',
                "emptyTable": "Tidak Ada Grup Yang Dimiliki"
            }
        });

        $('#create-group-form').submit(function(e){
            e.preventDefault();
            var form_data = new FormData(this);
            var url = $(this).attr('action');

            Swal.fire({
                    title: 'Apakah Yakin Membuat Grup?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'black',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Buat',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            cache: false,
                            processData: false,
                            contentType: false,
                            data: form_data,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Ok',
                                    confirmButtonColor: 'black',
                                    title: 'Berhasil!',
                                    text: response.message,
                                }).then((result) => {
                                    location.reload();

                                });

                            },
                            error: function (error) {
                                Swal.fire({
                                    confirmButtonColor: 'black',
                                    title: 'Kesalahan!',
                                    text: error.responseJSON.message,
                                    icon: 'error',
                                })
                                console.log(error);
                                $('#error-group-name').html('<li>' + error
                                    .responseJSON.nama_grup   + '</li>').css(
                                    'display', '');
                                $('#error-group-phone-no').html('<li>' + error
                                    .responseJSON.nomor_telepon_grup + '</li>').css(
                                    'display', '');
                                $('#error-group-institute').html('<li>' + error
                                    .responseJSON.instansi + '</li>').css(
                                    'display', '');
                                $('#error-group-email').html('<li>' + error
                                    .responseJSON.email_grup + '</li>').css(
                                    'display', '');
                                $('#error-group-address').html('<li>' + error
                                    .responseJSON.alamat + '</li>').css(
                                    'display', '');

                            },
                        });

                    }
                })
        });
    });

    </script>

    @endsection

</x-app-layout>
