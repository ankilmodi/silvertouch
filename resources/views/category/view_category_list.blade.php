@extends('layouts.master')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-10">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="box">
                    <div class="box-header box-header-title">
                      <h3 class="box-title">LIST OF CATEGORY</h3>
                    </div>      
                    <div class="box-header box-header-title">
                        <a href="{{ route('categoryCreate') }}" class="btn btn-default pull-left"><i
                                    class="fa fa-plus-square"></i> Add New Category</a>
                    </div>         
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
                                    <th>Category Name</th>
                                    <th>Category Image</th>   
                                    <th>Parent Category</th>
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
                lengthMenu: [[5, 25, 50, -1], [5, 25, 50, "All"]],
                bFilter: false,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('categoryDatatable') }}",
                    headers : {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    type : "POST",
                }, 
                columns: [
                    {data: 'id' ,name: 'id' },
                    {data: 'category_name', name: 'category_name', orderable: true, searchable: false},
                    {data: 'category_image', name: 'category_image', orderable: true, searchable: false},       
                    {data: 'parent_id', name: 'parent_id', searchable: false},
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

          
               // Handle form submission event 
               $('#frm-example').on('submit', function(e){
                  var form = this;
                  var rows_selected = oTable.column(0).checkboxes.selected();   
                   if(rows_selected.join(",")){
                    
                        var list_id = rows_selected.join(",");
                        
                        console.log(list_id);
                                                    
                        e.preventDefault();
                        $.ajax({
                            type : "POST",
                            url  : "{{ route('categoryDatatableDelete') }}",
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