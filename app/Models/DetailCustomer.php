<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'invoice',
        'namabarang', 
        'qty',
        'harga',
    ];
    protected $hidden = [
        'id', 
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    protected $dateFormat = 'd-m-Y';

    public function getByInvoice($invoice)
    {
        $query = DB::table('detail_customers')->select(
                'detail_customers.id',
                'detail_customers.invoice',
                'detail_customers.namabarang',
                'detail_customers.qty',
                'detail_customers.harga',
                'detail_customers.created_at',
                'detail_customers.updated_at', 
        )
        ->where('detail_customers.invoice','=',$invoice);
        
        $this->totalRows  = $query->count();
        $this->totalPages = ceil($this->totalRows/10);
        $data = $query->get();
        return $data;
    }
}
