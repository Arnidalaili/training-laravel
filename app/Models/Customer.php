<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \stdClass;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        $query = DB::table('customers');
        $query->select('*');
        $data = $query->get();
        return $data; 
    }

    public function getIndex($page,  $sidx, $sord, $filters, $limit)
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

        $filterResultsJSON = request()->input('filters');
        if ($filterResultsJSON) 
        {
            $filterResults = json_decode($filterResultsJSON, true);
            if (isset($filterResults['rules']) && is_array($filterResults['rules'])) 
            {
                foreach ($filterResults['rules'] as $filterRules) 
                {
                    $query->where($filterRules['field'], 'LIKE', '%' . $filterRules['data'] . '%');
                }
            }
        }

        $global_search = request()->input('global_search');
        if ($global_search) 
        {
            $global_search = '%' . $global_search . '%';
            $query->where(function ($query) use ($global_search) 
            {
                $query->where('invoice', 'LIKE', $global_search)
                    ->orWhere('nama', 'LIKE', $global_search)
                    ->orWhere('tanggal', 'LIKE', $global_search)
                    ->orWhere('jeniskelamin', 'LIKE', $global_search)
                    ->orWhere('saldo', 'LIKE', $global_search);     
            });
        }

        $count = $query->count();
        $totalPages = ($count > 0 && $limit > 0) ? ceil($count/$limit) : 0;

        $start = ($page - 1) * $limit;

        $data = $query->orderBy($sidx, $sord)->offset($start)->limit($limit)->get(); 

        $response = new stdClass();
        $response->page = $page;
        $response->total = $totalPages;
        $response->records = $count;
        $response->data = $data;

        $data = $response;
        return $data;
        
    }

    public function getReport($page,  $sidx, $sord, $start, $limit)
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
        
        $filterResultsJSON = request()->input('filters');
        if ($filterResultsJSON) {
            $filterResults = json_decode($filterResultsJSON, true);
            if (isset($filterResults['rules']) && is_array($filterResults['rules'])) {
                foreach ($filterResults['rules'] as $filterRules) {
                    $query->where($filterRules['field'], 'LIKE', '%' . $filterRules['data'] . '%');
                }
            }
        }

        $global_search = request()->input('global_search');
        if ($global_search) {
            $global_search = '%' . $global_search . '%';
            $query->where(function ($query) use ($global_search) {
                $query->where('invoice', 'LIKE', $global_search)
                    ->orWhere('nama', 'LIKE', $global_search)
                    ->orWhere('tanggal', 'LIKE', $global_search)
                    ->orWhere('jeniskelamin', 'LIKE', $global_search)
                    ->orWhere('saldo', 'LIKE', $global_search);     
            });
        }
        $data = $query->orderBy($sidx, $sord)->offset($start)->limit($limit)->get();

        $salesDetail = [];
        $dataDetail = [];
        $tempData = [];

        foreach($data as $index => $dataSales) 
        {
            $queryDetail = DB::table('detail_customers')
                ->where('invoice', $dataSales->invoice)
                ->get();
            $numRows = $queryDetail->count();
            $no = 1;

            foreach($queryDetail as $dataDetail)
            {
                $dataDetail->no = $no++;
                $salesDetail[] = (array)$dataSales + (array)$dataDetail;
            }
            if($numRows == 0)
            {
                $salesDetail[] = $dataSales;
            }
            $tempData['sales'] = $salesDetail;

            foreach ($tempData['sales'] as &$sale) 
            {
                $sale['tanggal'] = date('d-m-Y', strtotime($sale['tanggal']));
            }
        }
        return $tempData;
    }

    public function getPosition($sidx, $sord, $global_search, $filters, $search, $invoice)
    {
        $table = 'temp';
        Schema::create($table, function (Blueprint $table) {
            $table->integer('id');
            $table->increments('position')->unique();
            $table->string('invoice');
            $table->string('nama');
            $table->date('tanggal');
            $table->string('jeniskelamin');
            $table->float('saldo'); 
           
            $table->timestamps();
        });
        
        
        $query = DB::table('customers');
        $query->select(
        [
            'id', 
            'invoice', 
            'nama', 
            'tanggal', 
            'jeniskelamin',
            'saldo', 
            'created_at', 
            'updated_at'
        ]);
        
        
        if ($global_search) 
        {
            $query->where(function ($query) use ($global_search) 
            {
                $query->where('invoice', 'like', '%' . $global_search . '%')
                    ->orWhere('nama', 'like', '%' . $global_search . '%')
                    ->orWhere('tanggal', 'like', '%' . $global_search . '%')
                    ->orWhere('jeniskelamin', 'like', '%' . $global_search . '%')
                    ->orWhere('saldo', 'like', '%' . $global_search . '%');
                    
            });
        }

        if ($filters) 
        {
            foreach ($filters->rules as $rule) 
            {
                $query->where($rule->field, 'like', '%' . $rule->data . '%');
            }
        }

        $query->orderBy($sidx, $sord);

        $querys = $query->get();
        

        $data = $querys->map(function ($query,$index) 
        {
            return [
                'id' => $query->id,
                'invoice' => $query->invoice,
                'nama' => $query->nama,
                'tanggal' => $query->tanggal,
                'jeniskelamin' => $query->jeniskelamin,
                'saldo' => $query->saldo,
                'created_at' => $query->created_at,
                'updated_at' => $query->updated_at,
            ];
        })->toArray();
        
        DB::table($table)->insert($data);
        
        
        $position = DB::table($table)->where('invoice', $invoice)->value('position');
        
        $data = [
            'posisi' => $position,
        ];
        Schema::dropIfExists($table);

        return $data;
    }
}