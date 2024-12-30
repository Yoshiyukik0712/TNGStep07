<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::sortable()->get();

        // $keyWord = $request->input('keyWord');
        $companyId = $request->input('companyId');

        $query = Product::query();

        // if(isset($keyWord)){
        //     $query->where('product_name','LIKE',"%{$keyWord}%");
        // }

        // if(isset($companyId)){
        //     $query->where('company_id',$companyId);
        // }

        // if($min_price = $request->min_price){
        //     $query->where('price', '>=', $min_price);
        // }

        // if($max_price = $request->max_price){
        //     $query->where('price', '<=', $max_price);
        // }

        // if($min_stock = $request->min_stock){
        //     $query->where('stock', '>=', $min_stock);
        // }

        // if($max_stock = $request->max_stock){
        //     $query->where('stock', '<=', $max_stock);
        // }

        $products = $query->sortable()->orderBy('company_id', 'asc')->paginate(10);

        $company = new Company;
        $companies = $company->getLists();
       
        return view('products.index',[
            'products' => $products,
            'companies' => $companies,
            // 'keyWord' => $keyWord,
            'companyId' => $companyId
        ])->with('products', $products);
    }

    public function search(Request $request)
    {
        $query = Product::with('company');
        $input = $request->all();
        $companies = (new Company())->getAllCompanies();
        $model = new Product();
        $products = $model->getList($input);

        return response()->json([
            'products' => $products,
            'companies' => $companies,
            'price' => $request->minPrice,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $companies = Company::all();

        return view('products.create',compact('companies'));

        $this->validate($request, $this->validationRuleForCreate);

        try{

            DB::beginTransaction();

            if($request->hasFile('img_path')){

                $img_path = Product::IMAGE_DIR . Product::saveInage($request->file('img_path'));
            }

            $item = Product::make($request->all());
            $item->img_path = $img_path ?? '';

            $item->saveOrFail();

            DB::commit();

            return redirect('/create');
        }catch(\Throwable $e) {

            DB::rollback();

            Log::error($e);

            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        if($request->hasFile('img_path')){
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products',$filename,'public');
            $product->img_path = '/storage/' . $filePath;
        }

        $product->save();

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $product = Product::find($id);
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $companies = Company::all();

        return view('products.edit',compact('product','companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        try{
            
            DB::beginTransaction();

            $item = Product::findOrFail($id);

            if ($request->hasFile('img_path')) {

                $img_path = Product::IMAGE_DIR . Product::updateImage($request->file('img_path'), $item->img_path);
            }

            $item->fill($request->all());
            $item->img_path = $img_path ?? '';

            $item->saveOrFail();

            DB::commit();

            return redirect()->back();
        } catch (ModelNotFoundException $e) {

            throw $e;
        } catch (\Throwable $e) {

            DB::rollback();

            Log::error($e);

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        
            DB::beginTransaction();

            try {
            $product = Product::find($request->input('product'));
            Log::info($request);
            Log::info($request->input('product'));
            Log::info($product);
            //$products = Products::findOrFail($request->id);
            $product->delete();

            DB::commit();
            return response()->json(['success' => true]);

            } catch (\Exeption $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => '削除に失敗しました']);
            }
    }
}