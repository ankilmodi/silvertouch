@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
           
            <ol class="breadcrumb" align="center">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">add Product</li>
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
                            <h3 class="box-title">Add New Product</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('productStore') }}" method="post" enctype="multipart/form-data" data-parsley-validat id="form-product-add" >
                            <div class="box-body">
                                {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                            <label for="category_id " class="col-sm-4 control-label">Category</label>
                            <div class="col-sm-5"> 
                                       <select name="category_id" class="form-control" value="">
                                        <option value="">Select Category</option>
                                        @foreach ($category as $key=>$value)         
                                             <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                       
                                       </select>
                                        @if ($errors->has('category_id'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('category_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                             <div class="form-group{{ $errors->has('product_name') ? ' has-error' : '' }}">
                                    <label for="product_name" class="col-sm-4 control-label">Product Name</label>
                                    <div class="col-sm-5">
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                               placeholder="Enter Product Name">
                                        @if ($errors->has('product_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('product_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                             <div class="form-group{{ $errors->has('product_image') ? ' has-error' : '' }}">
                                    <label for="product_image" class="col-sm-4 control-label">Product Image</label>
                                    <div class="col-sm-5">
                            <input type="file" class="form-control" id="product_image" name="product_image">
                                        @if ($errors->has('product_image'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('product_image') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>   

                             <div class="form-group{{ $errors->has('product_description') ? ' has-error' : '' }}">
                                    <label for="product_description" class="col-sm-4 control-label">Product Description</label>
                                    <div class="col-sm-5">
                            <textarea class="form-control" id="product_description" name="product_description" placeholder="Enter Description" ></textarea>
                                        @if ($errors->has('product_description'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('product_description') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div> 

                            <div class="box-footer">
                                <button type="reset" class="btn btn-default">Clear</button>
                                <button type="submit" name="submit" class="btn btn-info pull-right">Save</button>
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
    $('#form-product-add').validate({
        rules: {
            category_id: {
                required: true
            },
            product_name: {
                required: true
            },
            product_description: {
                required: true
            },
            product_image: {
                required: true,
                extension: "jpeg|png|jpg|gif|svg"
            },
        },
        messages: {
            category_id: "Enter your category name",
            product_name: "Enter your product category",
            product_description: "Enter your product description",
            product_image: {
                required: "Enter your category image",
                extension: "Please enter a valid jpeg,png,jpg,gif",
            }
        }
    });
});
</script>
@endsection