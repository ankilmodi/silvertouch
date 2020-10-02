<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Session\TokenMismatchException;
use DataTables;
use Image;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::where('parent_id','!=','0')->pluck('category_name','id')->toArray();
        return view('product.view_product_list', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $category = Category::where('parent_id','!=','0')->pluck('category_name','id')->toArray();
         return view('product.add_product', compact('category')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
         
         $this->validate($request, array(
            'category_id' => 'required',
            'product_name' => 'required',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_description'  => 'required',
        ));

        $image = $request->file('product_image');
        $input['imagename'] = time().'.'.$image->extension();
     
        $destinationPath = public_path('/product_image');
        $img = Image::make($image->path());
        $img->resize(100, 59, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
   
        $destinationPath = public_path('/product_image');
        $image->move($destinationPath, $input['imagename']);


        $product = new Product();
        $product->category_id = $request->category_id;
        $product->product_name = $request->product_name;
        $product->product_image = $input['imagename'];
        $product->product_description = $request->product_description;
        $product->save();

         return redirect('/product-list')->with('message', 'Product Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::where('parent_id','!=','0')->pluck('category_name','id')->toArray();   
        $product_get = Product::where('id', $id)->first();
        return view('product.edit_product', compact('product_get','category'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $this->validate($request, array(
            'category_id' => 'required',
            'product_name' => 'required',
            'product_description'  => 'required',
        ));

       
         if(!empty($request->product_image)){

            $image = $request->file('product_image');
            $input['imagename'] = time().'.'.$image->extension();
         
            $destinationPath = public_path('/product_image');
            $img = Image::make($image->path());
            $img->resize(100, 59, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
       
            $destinationPath = public_path('/product_image');
            $image->move($destinationPath, $input['imagename']);
        }else{
             $input['imagename'] = $request['old_image'];
        }


        $product = Product::where('id',$id)->first();
        $product->category_id = $request->category_id;
        $product->product_name = $request->product_name;
        $product->product_image = $input['imagename'];
        $product->product_description = $request->product_description;
        $product->save();

        return redirect('product-list')->with('message', 'Successfully Product Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {    
        Product::find($id)->delete();
        return redirect('product-list')->with('message', 'Successfully Product Delete!');
    }

      /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function productDatatable(Request $request)
    {
         
        if ($request->ajax()) {
      
        $data = Product::select(['id','category_id','product_name','product_image'])
        ->orderBy('id', 'DESC')
        ->with('Category')->newQuery();
    
         return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {

                    $category_id = $request->category_id;
                    if ($category_id && !empty($category_id)) {
                        $instance->where(function ($query) use ($category_id) {
                            return $query->where('category_id','=', $category_id);
                        });
                    }

                    $product_name = $request->product_name;
                    if ($product_name && !empty($product_name)) {
                        $instance->where(function ($query) use ($product_name) {
                            return $query->where('product_name','=', $product_name);
                        });
                    }

                    $report_category_id = $request->report_category_id;
                    if ($report_category_id && !empty($report_category_id)) {
                        $instance->where(function ($query) use ($report_category_id) {
                            return $query->where('category_id','=', $report_category_id);
                        });
                    }
       
                })
                 ->editColumn('category_id', function($row){
                    $category_id = $row->Category[0]->category_name;
                    return $category_id;
                })
                 ->addColumn('product_image', function($row){
                     $btn = '<img src="'.env('APP_URL').'/product_image/'.$row->product_image.'" alt="Girl in a jacket" width="50" height="60">';
                    return $btn;
                })
                ->addColumn('action', function($row){
                     $btn = '<a href="' . route('productEdit', $row->id) .'" class="btn btn-primary btn-flat">EDIT</a>
                     <a href="' . route('productDelete', $row->id) .'" class="btn btn-danger btn-flat">DELETE</a>';
                    return $btn;
                })
                ->rawColumns(['action','product_image'])
                ->make(true);
          }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function productDatatableDelete(Request $request)
    {    

        Product::whereIn('id',[$request->multi_select_id])->delete();
        return ['success' => true, 'message' => 'Successfully Product Delete !!'];
    }
   
}