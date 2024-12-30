function sortEvent() {
    $(document).ready(function () {
        $('#product-table').tablesorter({
            headers: {
                1: { sorter: false },
                6: { sorter: false }
            },
        });
    });
}

function deleteEvent() {
    $('.btn-danger').click(function (e) {
        e.preventDefault()
        var deleteConfirm = confirm('削除してよろしいでしょうか？');
        if (deleteConfirm == true) {
            console.log('削除非同期開始');
            var clickEle = $(this);
            var product = clickEle.attr('data-product_id');
            console.log(product);

            var deleteTarget = clickEle.closest('tr');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: 'destroy',
                datatype: 'json',
                data: { product: product }
            })//通信が成功した時の処理
                .done(function () {
                    console.log('削除通信成功');
                    deleteTarget.remove();
                })//通信が失敗した時の処理
                .fail(function (e) {
                    console.log('通信後失敗');
                    console.log(e);

                })
        }
    });
}

$(function () {
    deleteEvent()
    $('#search-btn').click(function (event) {
        event.preventDefault();

        var name = $('#name').val();
        var company = $('#company').val();
        var minPrice = $('#minPrice').val();
        var maxPrice = $('#maxPrice').val();
        var minStock = $('#minStock').val();
        var maxStock = $('#maxStock').val();
        console.log(name);
        console.log(company);
        console.log(minPrice);
        console.log(maxPrice);
        console.log(minStock);
        console.log(maxStock);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "get",
            url: "search",
            datatype: "json",
            data: {
                keyword: name,
                companyId: company,
                minPrice: minPrice,
                maxPrice: maxPrice,
                minStock: minStock,
                maxStock: maxStock,
            }
        }) //通信成功時の処理
            .done(function (data) {
                console.log('処理開始');
                // var homeUrl = location.protocol+"//"+location.host;            
                var result = $('#search-result');
                result.empty(); //結果を一度クリア
                $.each(data.products, function (index, product) {
                    //console.log(data.products);
                    //console.log(data.companies[product.company_id -1]);
                    //console.log(data.companies[product.company_id -1].company_name);
                    var html = `<tr>
                    <td scope="row">${product.id}</td>
                    <td><img src="${product.img_path}" alt="商品画像" width="100"></td>
                    <td>${product.product_name}</td>
                    <td>￥${product.price}</td>
                    <td>${product.stock}</td>
                    <td>${data.companies[product.company_id - 1].company_name}</td>
                    <td><a href="products/${product.id}" class="btn btn-info btn-sm mx-1">詳細</a></td>
                    <td><a href="products/${product.id}/edit" class="btn btn-warning btn-sm mx-1">編集</a></td>
                    <td>
                        <button type="button" data-product_id="${product.id}" class="btn btn-danger btn-sm mx-1">削除</button>
                    </td>
                    </tr>`;
                    console.log(html);

                    result.append(html);
                    $('#product-table').trigger("update");
                    deleteEvent()
                    console.log("処理完了");
                });
            })
            .fail(function (e) {
                console.log('失敗');
                console.log(e);
            });
    });
});