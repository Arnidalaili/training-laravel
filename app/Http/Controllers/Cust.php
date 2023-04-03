<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\CustomerDetailModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public $title = 'Customer';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // $customers = DB::table('customers')->get();
        // $detailcust = DB::table('detail_customers')->get();
        // return view('customer.index', ['customer' => $customers], ['detail' => $detailcust]);
        
        // $customers = new Customer();

        // return response([
        //     'data'      => $customers->get()
        // ]);

        // $custdetail = new Cust
        $custdetail = new Customer();

        return response([
            'data'      => $custdetail->get()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fromAdd()
    {
        return view('customer.add');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fromEdit()
    {
        return view('customer.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fromDel()
    {
        return view('customer.del');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd('test');
        // DB::beginTransaction();
        // try{
        //     $customers = new Customer();
        //     $customers->invoice       = $request->invoice;
        //     $customers->nama          = $request->nama;
        //     $customers->tanggal       = $request->tanggal;
        //     $customers->jeniskelamin  = $request->jeniskelamin;
        //     $customers->saldo         = $request->saldo;


        //     $custdetail = new CustomerDetail();
        //     $custdetail->invoice      = $request->invoice;
        //     $custdetail->namabarang   = $request->namabarang;
        //     $custdetail->qty          = $request->qty;
        //     $custdetail->harga        = $request->harga;

        //     DB::commit();

        //     $response([
        //         'status'  => true,
        //         'message' => 'success',
        //         'data'    => $customers
        //     ], 201);

        //     return $response;
        // } 
        // catch (Exception $ex) 
        // {
        //     DB::rollBack();
        //     if ($ex instanceof NotFilterableException || $ex instanceof NotSortableException) {
        //         $statusCode = 400;
        //     } else {
        //         throw $ex;
        //     }

        //     return $this->respond([
        //         'message' => $ex->getMessage()
        //     ], $statusCode);
        // } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $customers = DB::table('customers')
        //         ->join('detail_customers', 'customers.invoice', '=', 'detail_customers.invoice_cust')
        //         ->where('customers.id', '=', $id)
        //         ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
