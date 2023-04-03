<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class CustomerController extends Controller
{
    public $modelName = Customer::class;
    
    public function show()
    {
        return view('customer.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $customer = new Customer();

        $filters = $request->input('filters', []);
        $limit = $request->input('rows', 10); 
        $page = $request->input('page', 1);
        $sidx = $request->input('sidx'); 
        $sord = $request->input('sord', 'asc');

        $filterResultsJSON = request()->input('filters');
        if ($filterResultsJSON) {
            $filterResults = json_decode($filterResultsJSON, true);
            if (isset($filterResults['rules']) && is_array($filterResults['rules'])) {
                foreach ($filterResults['rules'] as $filterRules) {
                    $customer->where($filterRules['field'], 'LIKE', '%' . $filterRules['data'] . '%');
                }
            }
        }

        $global_search = request()->input('global_search');
        if ($global_search) {
            $global_search = '%' . $global_search . '%';
            $customer->where(function ($customer) use ($global_search) {
                $customer->where('invoice', 'LIKE', $global_search)
                    ->orWhere('nama', 'LIKE', $global_search)
                    ->orWhere('tanggal', 'LIKE', $global_search)
                    ->orWhere('jeniskelamin', 'LIKE', $global_search)
                    ->orWhere('saldo', 'LIKE', $global_search);     
            });
        }

        $count = $customer->count();
        $totalPages = ($count > 0 && $limit > 0) ? ceil($count/$limit) : 0;

        $start = ($page - 1) * $limit;

        $data = $customer->orderBy($sidx, $sord)->offset($start)->limit($limit)->get()->toArray(); 
        $response = new stdClass();
        $response->page = $page;
        $response->total = $totalPages;
        $response->records = $count;
        $response->data = $data;

        $data = $response;
        return response()->json($data);
    }

    public function indexDetail(Request $request, $invoice) 
    {
        $custdetail = new CustomerDetail();
        return response([
            'data'      => $custdetail->getByInvoice($invoice),
            'totalRows' => $custdetail->totalRows,
            'totalPages'=> $custdetail->totalPages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formAdd()
    {
        return view('customer.add');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formEdit($invoice)
    {
        return view('customer.edit', ['invoice'=> $invoice]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formDel($invoice)
    {
        return view('customer.del', ['invoice'=> $invoice]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $request->validate
            ([
                'invoice' => 'required',
                'nama' => 'required',
                'tanggal' => 'required',
                'jeniskelamin' => 'required',
                'saldo' => 'required',
            ]);

            $customers = new Customer();
            $customers->invoice       = $request->input('invoice');
            $customers->nama          = $request->input('nama');
            $customers->tanggal       = date('Y-m-d', strtotime($request->input('tanggal')));
            $customers->jeniskelamin  = $request->input('jeniskelamin');
            $customers->saldo         = intval(str_replace(".", "", $request->input('saldo')));
            $customers->save();

            $inv = $customers->invoice;

            if($request->filled('NamaBarang'))
            {
                foreach ($request->input('NamaBarang') as $index => $item)
                {
                    $custdetail = new CustomerDetail();
                    $custdetail->invoice      = $inv;
                    $custdetail->namabarang   = $item;
                    $custdetail->qty          = $request->input('Qty')[$index];
                    $custdetail->harga        = str_replace(".", "", $request->input('Harga')[$index]);
                    $custdetail->save(); 
                }
            }

            DB::commit();
            $response = [
                'message' =>'Success',
                'invoice' => $inv,
            ];
            return response()->json($response); 
        }
        catch(Exception $e)
        {
            $res = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $invoice)
    {
        DB::beginTransaction();
        try
        {
            $request->validate
            ([
                'invoice' => 'required',
                'nama' => 'required',
                'tanggal' => 'required',
                'jeniskelamin' => 'required',
                'saldo' => 'required',
            ]);

            $customers = new Customer();
            $customers->invoice       = $request->input('invoice');
            $customers->nama          = $request->input('nama');
            $customers->tanggal       = date('Y-m-d', strtotime($request->input('tanggal')));
            $customers->jeniskelamin  = $request->input('jeniskelamin');
            $customers->saldo         = intval(str_replace(".", "", $request->input('saldo')));
            $customers->save();

            $inv = $customers->invoice;

            if($request->filled('NamaBarang'))
            {
                foreach ($request->input('NamaBarang') as $index => $item)
                {
                    $custdetail = new CustomerDetail();
                    $custdetail->invoice      = $inv;
                    $custdetail->namabarang   = $item;
                    $custdetail->qty          = $request->input('Qty')[$index];
                    $custdetail->harga        = str_replace(".", "", $request->input('Harga')[$index]);
                    $custdetail->save(); 
                }
            }

            DB::commit();
            $response = [
                'message' =>'Success',
                'invoice' => $inv,
            ];
            return response()->json($response); 
        }
        catch(Exception $e)
        {
            $res = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
            return response()->json($res);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($invoice)
    {
        
    }

    public function getPosition($invoice)
    {
        // var_dump('test');
        // die;
        $sidx = request('sidx', 'invoice');
        $sord = request('sord', 'asc');
        $global_search = request('global_search');
        $filters = request('filters') ? json_decode(request('filters')) : null;
        $search = request()->has('_search');

        $table = 'temporary';

        Schema::create($table, function (Blueprint $table) {
            $table->integer('id');
            $table->increments('position')->unique();
            $table->string('invoice');
            $table->string('nama');
            $table->date('tanggal');
            $table->string('jeniskelamin');
            $table->integer('saldo'); 
           
            $table->timestamps();
        });

        $customer = new Customer();
        
        $customer->select(['id', 'invoice', 'nama', 'tanggal','jeniskelamin', 'saldo',  'created_at', 'updated_at']);

        // dd($customers);

        if ($global_search) {
            $customer->where(function ($customer) use ($global_search) {
                $customer->where('invoice', 'like', '%' . $global_search . '%')
                    ->orWhere('nama', 'like', '%' . $global_search . '%')
                    ->orWhere('tanggal', 'like', '%' . $global_search . '%')
                    ->orWhere('jeniskelamin', 'like', '%' . $global_search . '%')
                    ->orWhere('saldo', 'like', '%' . $global_search . '%');
                    
            });
        }

        if ($filters) {
            foreach ($filters->rules as $rule) {
                $customer->where($rule->field, 'like', '%' . $rule->data . '%');
            }
        }

        $customer->orderBy($sidx, $sord);

        $customers = $customer->get();


        $data = $customers->map(function ($customer,$index) {
            return [
                'id' => $customer->id,
                'invoice' => $customer->invoice,
                'nama' => $customer->nama,
                'tanggal' => $customer->tanggal,
                'jeniskelamin' => $customer->jeniskelamin,
                'saldo' => $customer->saldo,
                'created_at' => $customer->created_at,
                'updated_at' => $customer->updated_at,
            ];
        })->toArray();

        DB::table($table)->insert($data);

        $position = DB::table($table)->where('id', $id)->value('position');

        $dataPosisi = [
            'posisi' => $position,

        ];
        Schema::dropIfExists($table);
        return response()->json($dataPosisi);
    }
}
