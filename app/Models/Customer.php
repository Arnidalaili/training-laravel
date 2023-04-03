<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';

    protected $fillable = [
        'id',
        'invoice',
        'nama', 
        'tanggal',
        'jeniskelamin',
        'saldo',
    ];
    protected $hidden = [
        'id', 
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d';

    public function get()
    {
        $query = DB::table('customers')->select(
            'customers.id',
            'customers.invoice',
            'customers.nama',
            'customers.tanggal',
            'customers.jeniskelamin',
            'customers.saldo',
            'customers.created_at',
            'customers.updated_at',
        );
        $data = $query->get(); 
        return $data; 
    }
}