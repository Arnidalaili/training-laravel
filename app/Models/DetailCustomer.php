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
                'customers.invoice as invoice',
                'detail_customers.namabarang',
                'detail_customers.qty',
                'detail_customers.harga',
                'detail_customers.created_at',
                'detail_customers.updated_at', 
            )
            ->join('customers', 'detail_customers.invoice', '=', 'customers.invoice')
            ->where('customers.invoice','=',$invoice);
        
        $this->totalRows  = $query->count();
        $this->totalPages = ceil($this->totalRows/10);
        $data = $query->get();
        return $data;
    }

    public function sort($query)
    {
        $sortfield = $_GET['sidx']; 
        $sortorder = $_GET['sord'];
        
        if (isset($sortfield)) 
        {   
            if ($sortorder == 'desc')  
            {
                $query  = $query->orderBy($sortfield, 'desc'); 
            }
            else if ($sortorder == 'asc') 
            {
                $query = $query->orderBy($sortfield, 'asc'); 
            }
        }
        return $query;
    }

    
    public function paginate($query)
    {
        
        $pagenum = $_GET['page']; 
        $pagesize = $_GET['rows']; 
        $start = ($pagenum - 1) * $pagesize;

        if (isset($pagenum)) 
        {
            $query = $query->offset($start)->limit($pagesize);
        }
        return $query;
    }
}
