@section('title', 'Catalogue Print Report')
@section('description', 'View and Download Product Catalogues')
@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Catalogue Report</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title mb-4">Catalogue Print Report</h4>
            </div>

            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Select Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('inv.reports.catalogue-report') }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <select name="product_id" id="product_id" class="form-control tom-select" 
                                            data-placeholder="Search and select product..." required>
                                        <option value=""></option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                                @if($product->model) - {{ $product->model }} @endif
                                                @if($product->brand) ({{ $product->brand->name }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa fa-search"></i> View Catalogue
                                    </button>
                                    <a href="{{ route('inv.reports.catalogue-report') }}" class="btn btn-warning">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if($selectedProduct)
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Catalogue Files: {{ $selectedProduct->name }}
                                @if($selectedProduct->model) - {{ $selectedProduct->model }} @endif
                                @if($selectedProduct->brand) ({{ $selectedProduct->brand->name }}) @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(count($catalogueFiles) > 0)
                                <div class="row">
                                    @foreach($catalogueFiles as $index => $file)
                                        @php
                                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                            $fileName = basename($file);
                                            
                                            // Determine file type and icon
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                                            $isPdf = $extension === 'pdf';
                                            $isImage = in_array($extension, $imageExtensions);
                                            
                                            if ($isPdf) {
                                                $icon = 'las la-file-pdf';
                                                $iconColor = 'text-danger';
                                                $bgColor = 'bg-danger-transparent';
                                            } elseif ($isImage) {
                                                $icon = 'las la-file-image';
                                                $iconColor = 'text-success';
                                                $bgColor = 'bg-success-transparent';
                                            } else {
                                                $icon = 'las la-file-alt';
                                                $iconColor = 'text-primary';
                                                $bgColor = 'bg-primary-transparent';
                                            }
                                        @endphp
                                        
                                        <div class="col-md-3 col-sm-6 mb-4">
                                            <div class="card file-card h-100 shadow-sm">
                                                <div class="card-body text-center">
                                                    @if($isImage)
                                                        <!-- Show thumbnail for images -->
                                                        <div class="file-thumbnail mb-3">
                                                            <img src="{{ $file }}" 
                                                                 alt="{{ $fileName }}" 
                                                                 class="img-fluid rounded"
                                                                 style="max-height: 150px; object-fit: cover; cursor: pointer;"
                                                                 onclick="window.open('{{ $file }}', '_blank')">
                                                        </div>
                                                    @else
                                                        <!-- Show icon for non-images -->
                                                        <div class="file-icon mb-3 {{ $bgColor }} rounded p-4">
                                                            <i class="{{ $icon }} {{ $iconColor }}" 
                                                               style="font-size: 80px; cursor: pointer;"
                                                               onclick="window.open('{{ $file }}', '_blank')"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    <h6 class="file-name text-truncate" title="{{ $fileName }}">
                                                        {{ Str::limit($fileName, 25) }}
                                                    </h6>
                                                    
                                                    <p class="text-muted small mb-3">
                                                        {{ strtoupper($extension) }} File
                                                    </p>
                                                    
                                                    <div class="btn-group btn-group-sm w-100" role="group">
                                                        <a href="{{ $file }}" 
                                                           target="_blank" 
                                                           class="btn btn-outline-primary">
                                                            <i class="las la-eye"></i> View
                                                        </a>
                                                        <a href="{{ $file }}" 
                                                           download 
                                                           class="btn btn-outline-success">
                                                            <i class="las la-download"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                            @else
                                <div class="alert alert-warning text-center" role="alert">
                                    <i class="las la-exclamation-triangle fs-3"></i>
                                    <p class="mb-0 mt-2">No catalogue files uploaded for this product.</p>
                                    {{-- <a href="{{ route('inv.reports.edit', $selectedProduct->id) }}" 
                                       class="btn btn-sm btn-primary mt-3">
                                        <i class="las la-upload"></i> Upload Catalogue Files
                                    </a> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_styles')
<style>
    .file-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    
    .file-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 150px;
    }
    
    .file-thumbnail img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .bg-danger-transparent {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .bg-success-transparent {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-primary-transparent {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .file-name {
        font-weight: 600;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('page_scripts')

@endsection