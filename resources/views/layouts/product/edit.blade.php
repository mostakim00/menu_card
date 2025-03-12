@extends('app')

@section('title', 'Create Category')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        textarea.form-control,
        textarea.typeahead,
        textarea.tt-query,
        textarea.tt-hint,
        .select2-container--default .select2-selection--single textarea.select2-search__field,
        .select2-container--default textarea.select2-selection--single,
        textarea.asColorPicker-input {
            min-height: 12rem;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Product</h4>
                        <p class="card-description">Setup Mainstream Entertainment, please provide your <code>valid
                                data</code>.</p>
                        <div class="mt-4">
                            <form class="forms-sample" action="{{ route('product.update', ['id' => $product->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="category_type">Category Name:</label>
                                    <select class="form-control form-control-md border-left-0 @error('name') is-invalid @enderror" id="item_id" name="item_id">
                                        <option>Select product category</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $product->item_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_name">Product Name:</label>
                                    <input type="text"
                                        class="form-control form-control-md border-left-0 @error('name') is-invalid @enderror"
                                        placeholder="Please enter your product name" name="name" id="product_name"
                                        value="{{ $product->name }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="product_price">Product Price:</label>
                                    <input type="text"
                                        class="form-control form-control-md border-left-0 @error('price') is-invalid @enderror"
                                        placeholder="Please enter your product price" name="price" id="product_price"
                                        value="{{ $product->price }}">
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Product Description:</label>
                                    <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="category_image">Category Image:</label>
                                        <input type="file"
                                            class="form-control form-control-md border-left-0 dropify @error('image') is-invalid @enderror"
                                            name="image" id="image" data-default-file="{{ asset($product->image) }}">
                                        @error('image')
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('item.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
