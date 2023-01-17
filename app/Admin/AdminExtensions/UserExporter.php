<?php

namespace App\Admin\AdminExtensions;

use Illuminate\Support\Facades\Request;
use App\Admin\AdminExtensions\BasePdfExporter;
use App\Models\User;
use Modules\Cooperative\Entities\DinasSuggestion;

class UserExporter extends BasePdfExporter
{
    protected $fileName = 'Daftar Pelanggan.pdf';

    protected $title = 'Daftar Pelanggan';

    protected $headings = [
        'No',
        'Nama Pengguna',
        'Nama Lengkap',
        'E-Mail',
        'Nomor Handphone',
        'Alamat',
        'Kota',
        'Institut/Tempat Kerja',
        'Jenis Kelamin'
    ];

    protected $columns = [
        'username',
        'name',
        'email',
        'phone_number',
        'address',
        'city',
        'institute',
        'gender',
    ];



    public function __construct()
    {


        $model = User::select($this->columns);

        if (Request::input('username')) {
            $model = $model->where('username', 'LIKE', '%' . request('username') . '%');
        }

        if (Request::input('name')) {
            $model = $model->where('name', 'LIKE', '%' . request('name') . '%');
        }
        if (Request::input('institute')) {
            $model = $model->where('institute', 'LIKE', '%' . request('institute') . '%');
        }

        $this->data = $model->get()->toArray();
        $no = 0;
        $gender = [
            0 => 'Perempuan',
            1 => 'Laki-Laki',
        ];

        foreach ($this->data as $data) {
            $this->data[$no++]['gender'] = $gender[$data['gender']];
        }
    }
}
