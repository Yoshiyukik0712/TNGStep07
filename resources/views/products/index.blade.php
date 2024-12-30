@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>


    <div class="search mt-5">
        <form class="row g-3">
            <div class="col-sm-12 col-md-3">
                {{-- <label class="col-sm-2 col-form-label">商品名</label> --}}
                <input type="text" class="form-control" name="keyword" id="name" placeholder="商品名">
            </div>
            <div class="col-sm-12 col-md-3">
                {{-- <label class="col-sm-4 col-form-label">メーカー名</label>     --}}
                <select class="form-control" name="companyId" id="company">
                    <option value="">メーカー名</option>
                    @foreach($companies as $id => $company_name)
                        <option value="{{ $id }}">
                            {{ $company_name }}
                        </option>
                    @endforeach
                </select>
            </div>   
            <div class="col-sm-12 col-md-3">
                <input type="number" name="min_price" class="form-control" placeholder="最小価格" id="minPrice">
            </div> 
            <div class="col-sm-12 col-md-3">
                <input type="number" name="max_price" class="form-control" placeholder="最大価格" id="maxPrice">
            </div>
            <div class="col-sm-12 col-md-3">
                <input type="number" name="min_stock" class="form-control" placeholder="最小在庫" id="minStock">
            </div>
            <div class="col-sm-12 col-md-3">
                <input type="number" name="max_stock" class="form-control" placeholder="最大在庫" id="maxStock">
            </div>                           
            <div class="col-sm-12 col-md-1">
                {{-- <button class="btn btn-outline-secondary" type="submit">検索</button> --}}
                <button class="btn btn-primary" id="search-btn">検索</button>
            </div>
        </form>
    </div>
    
    <div class="card products mt-5">
        <table class="table table-striped tablesorter" id="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <th colspan="3"><a href="{{ route('products.create') }}" class="btn btn-warning mb-1">新規登録</a></th>
                </tr>
            </thead>
            <tbody id="search-result">
            @foreach ($products as $product)
                <tr>
                    <td scope="row">{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>    
                    <td>{{ $product->product_name }}</td>
                    <td>￥{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td><a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a></td>
                    <td><a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm mx-1">編集</a></td>
                        {{-- <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"> --}}
                            @csrf
                            @method('DELETE')
                            {{-- <button type="submit" class="btn btn-danger btn-sm mx-1" onclick='return confirm("削除しますか？");'>削除</button> --}}
                    <td><button data-product_id="{{ $product->id }}" type="submit" class="btn btn-danger btn-sm mx-1">削除</button></td>
                        {{-- </form> --}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection      