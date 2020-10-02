@extends('layouts.master')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-11">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="box">
                    <div class="box-header box-header-title">
                        <h3 class="box-title">LIST OF PRODUCT</h3>
                        <a href="{{ route('productCreate') }}" class="btn btn-default pull-right"><i
                                    class="fa fa-plus-square"></i> Add New Product</a>
                    </div>

                     <section class="content">
            <!-- right column -->
            <div class="col-md-10 col-md-offset-1">
               
                <div class="row">
                    <div class="box box-info">
                      <form id="form-filter" class="form-horizontal">
                            <div class="box-body">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="product_name" class="col-sm-4 control-label">Product Name</label>
                                    <div class="col-sm-5">
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                               placeholder="Enter Product Name">
                                      
                                    </div>
                                </div>
                                <div class="form-group">
                            <label for="parent_id" class="col-sm-4 control-label">Category Name</label>
                            <div class="col-sm-5"> 
                                       <select name="category_id" id="category_id" class="form-control" value="">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $key=>$value)         
                                             <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                       
                                       </select>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <CENTER> <button type="button" id="btn-filter" class="btn btn-xs btn-primary">Serche</button>
                                 <button type="button" id="btn-reset" class="btn btn-xs btn-danger">Reset</button></CENTER>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
                    <div class="box-body">
                   <form id="frm-example" action="/path/to/your/script.php" method="POST">
                    <div class="dt-buttons">
                        <p>
                            <button class="dt-button buttons-collection buttons-page-length pull-right btn-danger">Delete</button>
                        </p>      
                            </div>
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                 <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                                 <th>Product Name</th>
                                <th>Product Image</th>
                                <th>Category</th>
                                <th>ACTION</th>
                            </tr>
                        </table>
                       </form> 
                    </div>
                </div>
            </div>
        </div>
    </section>

@include('include.footer')

<script type="text/javascript"> 
        $(document).ready(function() {

             var oTable = $('#myTable').DataTable({
                       lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        bFilter: false,
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        ajax: {
                            url: "{{ route('productDatatable') }}",
                            type: "POST",
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                           data: function (d) {
                                d.product_name = $('input[name=product_name]').val();
                                d.category_id = $('select[name=category_id]').val();                      
                            }
                        }, 
                        columns: [
                            {data: 'id', name: 'id'},
                            {data: 'product_name', name: 'product_name', searchable: false},
                            {data: 'product_image', name: 'product_image', orderable: true, searchable: false},
                            {data: 'category_id', name: 'category_id', orderable: true, searchable: false},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                         order: [ 0, "desc" ],
                        'columnDefs': [
                         {
                            'targets': 0,
                            'checkboxes': {
                               'selectRow': true
                            }
                         }
                      ],
                      'select': {
                         'style': 'multi'
                      },
                    });

          
                $('#btn-filter').click(function(){ 
                    oTable.ajax.reload();  
                });
                
                $('#btn-reset').click(function(){ 
                    $('#form-filter')[0].reset();
                    oTable.ajax.reload();  
                }); 


                 $('#frm-example').on('submit', function(e){
                  var form = this;
                  var rows_selected = oTable.column(0).checkboxes.selected();   
                   if(rows_selected.join(",")){
                    
                        var list_id = rows_selected.join(",");
                        
                        console.log(list_id);
                                                    
                        e.preventDefault();
                        $.ajax({
                            type : "POST",
                            url  : "{{ route('productDatatableDelete') }}",
                            headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data : { multi_select_id : list_id},
                            success: function(data){
                                    oTable.ajax.reload();
                                     toastr["success"](data['message'])                           
                                    }
                                });
                            
                                } else{
                                    toastr["info"]("Please select any one required");
                               }
                                    

                          // Iterate over all selected checkboxes
                          $.each(rows_selected, function(index, rowId){
                             // Create a hidden element 
                             $(form).append(
                                 $('<input>')
                                    .attr('type', 'hidden')
                                    .attr('name', 'id[]')
                                    .val(rowId)
                             );
                          });
                                             
                          // Output form data to a console     
                          $('#example-console-rows').text(rows_selected.join(","));
                          
                          // Output form data to a console     
                          $('#example-console-form').text($(form).serialize());
                           
                          // Remove added elements
                          $('input[name="id\[\]"]', form).remove();
                           
                          // Prevent actual form submission
                          e.preventDefault();
                       });   

        
         }); 
    </script>

@endsection