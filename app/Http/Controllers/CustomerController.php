<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class CustomerController extends Controller
{
    public $modelName = Customer::class;
    public $modelDetail = DetailCustomer::class;
    
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

        $data = $customer->getIndex($page, $sidx, $sord, $filters, $limit);
        
        return response()->json($data);

    }

    public function indexDetail(Request $request, $invoice) 
    {
        $custdetail = new DetailCustomer();
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

            if($request->filled('namabarang'))
            {
                $customers = new Customer();
                $customers->invoice       = $request->input('invoice');
                $customers->nama          = $request->input('nama');
                $customers->tanggal       = date('Y-m-d', strtotime($request->input('tanggal')));
                $customers->jeniskelamin  = $request->input('jeniskelamin');
                $customers->saldo         = intval(str_replace(".", "", $request->input('saldo')));
                $customers->save();

                $inv = $customers->invoice;
                foreach ($request->input('namabarang') as $index => $item)
                {
                    $custdetail = new DetailCustomer();
                    $custdetail->invoice      = $inv;
                    $custdetail->namabarang   = $item;
                    $custdetail->qty          = $request->input('qty')[$index];
                    $custdetail->harga        = str_replace(".", "", $request->input('harga')[$index]);
                    $custdetail->save(); 
                }
            }
            else
            {
                $customers = new Customer();
                $customers->invoice       = $request->input('invoice');
                $customers->nama          = $request->input('nama');
                $customers->tanggal       = date('Y-m-d', strtotime($request->input('tanggal')));
                $customers->jeniskelamin  = $request->input('jeniskelamin');
                $customers->saldo         = intval(str_replace(".", "", $request->input('saldo')));
                $customers->save();
            }
            $inv = $customers->invoice;
            $invoice = JSON.parse($inv);
            dd($invoice);

            DB::commit();
            $response = [
                'message' =>'Success',
                'data' => $inv,
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
    public function update(Request $request)
    {
        $request->validate
        ([
            'invoice'      => 'required',
            'nama'         => 'required',
            'tanggal'      => 'required',
            'jeniskelamin' => 'required',
            'saldo'        => 'required',
        ]);

        $customers = new Customer();
        $invoice       = $request->input('invoice');
        $nama          = $request->input('nama');
        $tanggal       = date('Y-m-d', strtotime($request->input('tanggal')));
        $jeniskelamin  = $request->input('jeniskelamin');
        $saldo         = intval(str_replace(".", "", $request->input('saldo')));
        
        try
        {
            DB::beginTransaction();
            
            $customers = Customer::where('invoice', $invoice)->firstOrFail();
            $customers->invoice       = $invoice;
            $customers->nama          = $nama;
            $customers->tanggal       = $tanggal;
            $customers->jeniskelamin  = $jeniskelamin;
            $customers->saldo         = $saldo;
            $customers->save();

            $inv = $customers->invoice;

            if($request->filled('namabarang'))
            {   
                DB::table('detail_customers')
                ->where('invoice',$inv)
                ->delete();

                foreach ($request->input('namabarang') as $index => $item)
                {
                    $custdetail = new DetailCustomer();
                    $custdetail->invoice      = $inv;
                    $custdetail->namabarang   = $item;
                    $custdetail->qty          = $request->input('qty')[$index];
                    $custdetail->harga        = str_replace(".", "", $request->input('harga')[$index]);
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
    public function destroy(Request $request)
    {
        $invoice = $request->input('invoice');
        try
        {
            DB::beginTransaction();
            
            DB::table('detail_customers')
                ->where('invoice' , $invoice)
                ->delete();

            DB::table('customers')
                ->leftJoin('detail_customers', 'customers.invoice', '=', 'detail_customers.invoice')
                ->where('customers.invoice', $invoice) 
                ->delete(); 

            DB::commit();  

            $response = [
                'message' =>'Success',
                'invoice' => $invoice,
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $customer = new Customer();

        $page = $request->page;
        $sidx = $request->sidx; 
        $sord = $request->sord;
        $start = $request->start-1;
        $limit = $request->limit - $start;

        $data = $customer->getReport($page, $sidx, $sord, $start, $limit);
       
        return view('customer.report', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $invoice   = $request->invoice;

        $customer = new Customer();
        $data     = $customer->where('invoice', $invoice)->get();

        $custDetail = new DetailCustomer();
        $dataDetail = $custDetail->where('invoice', $invoice)->get();

        return view('customer.export', ['data' => $data, 'detail' => $dataDetail]);
    }
 
    public function position($invoice)
    {
        dd('test');
        $customer = new Customer();
       
        $sidx = request('sidx', 'invoice');
        $sord = request('sord', 'asc');
        $global_search = request('global_search');
        $filters = request('filters') ? json_decode(request('filters')) : null;
        $search = request()->has('_search');

        $data = $customer->getPosition($sidx, $sord, $global_search, $filters, $search);
        
        return response()->json($data);
    }
}
