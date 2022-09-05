<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class ProductAjaxController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {

            $data = Product::with('getCategory')->latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('image',function($row){
                        $url= asset('storage/'.$row->image);
                        return '<img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" />';
                    })
                    ->addColumn('details',function($row){
                        return substr_replace($row->details,"...",10);
                    })
                    ->addColumn('category_name', function($row){
                        return $row->getCategory->name;
                    })
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action','image'])
                    ->make(true);
        }

        $category = Category::all();
        $uri = $request->path();
        return view('productAjax',compact(['category','uri']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $requiredField = array();
        $requiredField['name'] = 'required|min:3|max:50';
        $requiredField['detail'] = 'required|min:15|max:250';
        $requiredField['price'] = 'required|integer|max:10000|min:0';
        $requiredField['categories_id']= 'required|exists:categories,id|integer';

        if(!$request->product_id){
            $requiredField['image'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        // Validation
        $request->validate($requiredField);

        //initialized data
        $data  = array();
        $data['user_id'] = Auth::user()->id;
        $data['name'] = $request->name;
        $data['detail'] = $request->detail;
        $data['price'] = $request->price;
        $data['categories_id'] = $request->categories_id;




        if ($request->image) {
            $image_path = $request->file('image')->store('image', 'public');
            $data['image'] = $image_path;
        }

        //
        Product::updateOrCreate([
            'id' => $request->product_id
        ],
        $data);

        return response()->json(['success'=>'Product saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $product = Product::find($id);
        return response()->json($product);
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
        Product::find($id)->delete();
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
