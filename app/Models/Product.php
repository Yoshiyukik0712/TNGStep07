<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasFactory;
    use Sortable;

    public $sortable = [
        'id', 
        'product_name', 
        'price', 
        'stock', 
        'company' 
    ];

    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getList($input) {
        
    $keyword = '';
    $companyId = '';
    $minPrice = '';
    $maxPrice = '';
    $minStock = '';
    $maxStock = '';

    if(array_key_exists('keyword', $input)) {
        $keyword = $input['keyword'];
        }     
    if(array_key_exists('companyId', $input)) {
        $companyId = $input['companyId'];
        }
    if(array_key_exists('minPrice', $input)) {
        $minPrice = $input['minPrice'];
        }
    if(array_key_exists('maxPrice', $input)) {
        $maxPrice =$input['maxPrice'];
        }
    if(array_key_exists('minStock', $input)) {
        $minStock =$input['minStock'];
        }
    if(array_key_exists('maxStock', $input)) {
        $maxStock =$input['maxStock'];
        }

    $query = Product::query();

    if(!empty($keyword)) {
        $query->where('name', 'LIKE', "%{$keyword}%");
        }
    if(!empty($companyId)) {
        $query->where('company_id', 'LIKE', "$companyId");
        }

    if((isset($minPrice)) && (isset($maxPrice))) {
        $query->whereBetween('price',[$minPrice, $maxPrice]);
        } elseif(isset($minPrice)) {
        $query->where('price', '>=', $minPrice);
        } elseif(isset($maxPrice)) {
        $query->where('price', '<=', $maxPrice);
        }

    if((isset($minStock)) && (isset($maxStock))) {
        $query->whereBetween('stock',[$minStock, $maxStock]);
        } elseif(isset($minStock)) {
        $query->where('stock', '>=', $minStock);
        } elseif(isset($maxStock)) {
        $query->where('stock', '<=', $maxStock);
        }

    if(empty($keyword) && empty($companyId) && empty($minPrice) && empty($maxPrice) && empty($minStock) && empty($maxStock)) {
        $products = Product::sortable()->get();
        }else{
        $products = $query->sortable()->get();
        }
           // dd($products);
        return $products; //controllerで呼び出した$productsに処理した結果を返している
    }
}