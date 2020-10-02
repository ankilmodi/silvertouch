@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <ol class="breadcrumb" align="center">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Edit Category</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- right column -->
            <div class="col-md-8 col-md-offset-1">
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div class="row">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Category</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('categoryUpdate', $category_get->id) }}" method="post" enctype="multipart/form-data" data-parsley-validat id="form-category-edit">
                            <div class="box-body">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('category_name') ? ' has-error' : '' }}">
                                    <label for="category_name" class="col-sm-4 control-label">Category Name</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" value="{{ $category_get->category_name }}" id="category_name" name=" category_name"
                                               placeholder="Enter Category Name">
                                        @if ($errors->has('category_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('category_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                            <label for="parent_id" class="col-sm-4 control-label">Parent Category Name</label>
                            <div class="col-sm-5"> 
                                       <select name="parent_id" class="form-control" value="">
                                        @foreach ($category as $key=>$value)
                                        <option value="{{ $key }}" {{ $category_get->parent_id == $key ? "selected" : "" }}>{{ $value }}</option>
                                   		 @endforeach
                                       </select>
                                        @if ($errors->has('parent_id'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('parent_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                              <div class="form-group{{ $errors->has('category_image') ? ' has-error' : '' }}">
                                    <label for="category_image" class="col-sm-4 control-label">Category Image</label>
                                    <div class="col-sm-5">

                                           <img id="category"  style="width: 31%;height: 5%;" class="form-control" src="{{ env('APP_URL').'/category_image/'.$category_get->category_image }}"  alt="Image not found" />
                                            <input type="file" class="form-control"  value="" id="category_image_logo" name="category_image">
                                            <input type="hidden" class="form-control"  value="{{ $category_get->category_image }}" name="old_image">
                                        @if ($errors->has('category_image'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('category_image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> 
                            <div class="box-footer">
                                <button type="reset" class="btn btn-default">Clear</button>
                                <button type="submit" name="submit" class="btn btn-info pull-right">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@include('include.footer')
<script type="text/javascript"> 
 $(document).ready(function () {
    $('#form-category-edit').validate({
        rules: {
            category_name: {
                required: true
            },
            parent_id: {
                required: true
            },
        },
        messages: {
            category_name: "Enter your category name",
            parent_id: "Enter your parent category"
        }
    });
});

function readURL_0(input) {
    console.log(input);
    if (input.files && input.files[0]) {
        var reader0 = new FileReader();
        reader0.onload = function (e) {
            $('#category').attr('src', e.target.result);
        }
        reader0.readAsDataURL(input.files[0]);
    }
    else{
        $('#category').attr('src', $('#category_image').val());
    }
}
$("#category_image_logo").change(function(){
    readURL_0(this);
});

 </script>        
@endsection