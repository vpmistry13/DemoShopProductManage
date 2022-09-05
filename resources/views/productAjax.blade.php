@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h2>Manage Products</h2>
        </div>
        <div class="col-md-6">
            <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct">Add</a>
        </div>
    </div>

     
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category Name</th>
                <th>Details</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="productForm" name="productForm" class="form-horizontal">
                   <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                            <span class="error error_name"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="detail" name="detail" required="" placeholder="Enter Details" class="form-control"></textarea>
                            <span class="error error_detail"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="price" name="price" min="0" max="9999" placeholder="Enter Price" value="" maxlength="10" required="">
                            <span class="error error_price"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-12">
                            <select name="categories_id" class="form-control" id="categories_id">
                                <option value="">Select Category</option>
                                @foreach($category as $cat)
                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                @endforeach
                            </select>
                            <span class="error error_categories_id"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" name="image"/>
                            <span class="error error_image"></span>
                        </div>
                        <div class="col-sm-12 image_area">

                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

      /*------------------------------------------
       --------------------------------------------
       Pass Header Token
       --------------------------------------------
       --------------------------------------------*/
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });

      /*------------------------------------------
      --------------------------------------------
      Render DataTable
      --------------------------------------------
      --------------------------------------------*/
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('products-manage.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'image',name:'image', orderable: false, searchable: false},
              {data: 'name', name: 'name'},
              {data: 'category_name' , name:'category_name'},
              {data: 'detail', name: 'detail'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

      /*------------------------------------------
      --------------------------------------------
      Click to Button
      --------------------------------------------
      --------------------------------------------*/
      $('#createNewProduct').click(function () {
          $('#saveBtn').val("create-product");
          $('#product_id').val('');
          $('#productForm').trigger("reset");
          $('#modelHeading').html("Create New Product");
          $('#ajaxModel').modal('show');
      });

      /*------------------------------------------
      --------------------------------------------
      Click to Edit Button
      --------------------------------------------
      --------------------------------------------*/
      $('body').on('click', '.editProduct', function () {
        var product_id = $(this).data('id');
        $.get("{{ route('products-manage.index') }}" +'/' + product_id +'/edit', function (data) {
            $('#modelHeading').html("Edit Product");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal('show');
            $('#product_id').val(data.id);
            $('#name').val(data.name);
            $('#detail').val(data.detail);
            $('#price').val(data.price);
            $('#categories_id').val(data.categories_id);
            if(data.image){
                $('.image_area').html("<img src='/storage/"+data.image+"' width='250'/>");
            }
        })
      });

      /*------------------------------------------
      --------------------------------------------
      Create Product Code
      --------------------------------------------
      --------------------------------------------*/
      $('#saveBtn').click(function (e) {
          e.preventDefault();
          $(this).html('Sending..');

          var fd = new FormData(document.getElementById('productForm') );
          let items =  $('#productForm').find('.error');
            items.map((key,item) => {
                $(item).html('')
            })

          $.ajax({
            data: fd,
            url: "{{ route('products-manage.store') }}",
            type: "POST",
            processData: false,
            contentType: false,
            success: function (data) {

                $('#productForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();
                $('#saveBtn').html('Save Changes');
                $('.image_area').html('');


            },
            error: function (data) {
                console.log('Error:', JSON.parse(data.responseText));
                if(data && data.responseText){
                    const{errors} = JSON.parse(data.responseText);
                    console.log(errors)

                    if(errors){
                        Object.entries(errors).map((item,key) => {
                            //initialized error in forms
                            $('.error_'+item[0]).html(item[1][0]);
                        })
                    }
                }
                $('#saveBtn').html('Save Changes');
            }
        });
      });

      /*------------------------------------------
      --------------------------------------------
      Delete Product Code
      --------------------------------------------
      --------------------------------------------*/
      $('body').on('click', '.deleteProduct', function () {

          var product_id = $(this).data("id");
          confirm("Are You sure want to delete !");

          $.ajax({
              type: "DELETE",
              url: "{{ route('products-manage.store') }}"+'/'+product_id,
              success: function (data) {
                  table.draw();
              },
              error: function (data) {
                  console.log('Error:', data);
              }
          });
      });

    });
  </script>

@endsection



