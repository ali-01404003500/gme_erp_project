@section('title', 'Hotspot Setup')
@section('description', 'Hotspot Setup')
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.hrm-settings-hotspot-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.settings.hotspots.create'))
                                    <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        Add New
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.hrm-settings-hotspot-menu-title') }}
                            </h4>
                            <x-error-alart />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $hotspots])'
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 8%">Sl</th>
                                            <th class="text-center">Branch</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Radius (m)</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hotspots as $key => $hotspot)
                                            <tr>
                                                <td class="text-center">{{ ($hotspots->currentPage() - 1) * $hotspots->perPage() + $loop->iteration }}</td>
                                                <td class="text-center">{{ $hotspot->branch->name ?? 'N/A' }}</td>
                                                <td class="text-center">{{ $hotspot->name ?? '-' }}</td>
                                                <td class="text-center">
                                                    <small>
                                                        <a href="https://www.google.com/maps?q={{ $hotspot->latitude }},{{ $hotspot->longitude }}" target="_blank" rel="noopener noreferrer">
                                                            <i class="las la-map-marker text-danger"></i>
                                                            {{ number_format($hotspot->latitude, 6) }}, {{ number_format($hotspot->longitude, 6) }}
                                                        </a>
                                                    </small>
                                                </td>
                                                <td class="text-center">{{ $hotspot->radius }}</td>
                                                <td class="text-center">
                                                    @if ($hotspot->status == 1)
                                                        <span class="badge badge-round badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-round badge-danger">Inactive</span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group"
                                                        aria-label="Small button group">

                                                        @if (hasPermission('hrm.settings.hotspots.update'))
                                                            <button type="button" data-action="{{ route('hrm.settings.hotspots.update', $hotspot->id) }}" data-data="{{$hotspot}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                                                <i class="far fa-edit"></i>
                                                            </button>
                                                        @endif

                                                        @if (hasPermission('hrm.settings.hotspots.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.settings.hotspots.destroy', $hotspot->id) }}"
                                                                class="btn btn-outline-danger delete-confirm" title="Delete"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">Add Hotspot</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('hrm.settings.hotspots.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="col-form-label">Branch <span class="text-danger">*</span></label>
                                            <select class="form-control tom-select" name="branch_id" required>
                                                <option value="">Select Branch</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="col-form-label">Hotspot Name</label>
                                            <input type="text" name="name" class="form-control" placeholder="Hotspot Name (Optional)">
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="col-form-label">Radius (meters) <span class="text-danger">*</span></label>
                                            <select class="form-control tom-select" name="radius" required>
                                                <option value="">Select Radius</option>
                                                <option value="50">50 meters</option>
                                                <option value="100">100 meters</option>
                                                <option value="200">200 meters</option>
                                                <option value="300">300 meters</option>
                                                <option value="500">500 meters</option>
                                                <option value="1000">1 kilometer</option>
                                                <option value="2000">2 kilometers</option>
                                                <option value="5000">5 kilometers</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="col-form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-4">
                                            <label class="col-form-label">Location (Map) <span class="text-danger">*</span></label>
                                            <div id="map" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                                            <input type="hidden" name="latitude" id="latitude" required>
                                            <input type="hidden" name="longitude" id="longitude" required>
                                            <small class="text-muted">Click on the map to select location</small>
                                        </div>

                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Address</label>
                                        <div class="col-sm-12">
                                            <textarea name="address" class="form-control" rows="2" placeholder="Enter address (Optional)"></textarea>
                                        </div>
                                    </div>

                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit Hotspot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Branch <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control tom-select" name="branch_id" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Hotspot Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="name" class="form-control" placeholder="Hotspot Name (Optional)">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Radius (meters) <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control tom-select" name="radius" required>
                                    <option value="">Select Radius</option>
                                    <option value="50">50 meters</option>
                                    <option value="100">100 meters</option>
                                    <option value="200">200 meters</option>
                                    <option value="300">300 meters</option>
                                    <option value="500">500 meters</option>
                                    <option value="1000">1 kilometer</option>
                                    <option value="2000">2 kilometers</option>
                                    <option value="5000">5 kilometers</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Location (Map) <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <div id="editMap" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                                <input type="hidden" name="latitude" id="edit_latitude" required>
                                <input type="hidden" name="longitude" id="edit_longitude" required>
                                <small class="text-muted">Click on the map to select location</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Address</label>
                            <div class="col-sm-12">
                                <textarea name="address" class="form-control" rows="2" placeholder="Enter address (Optional)"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map, marker;
        let editMap, editMarker;
        
        // Initialize map when create modal opens
        $('#createModal').on('shown.bs.modal', function () {
            if (!map) {
                // Default to Dhaka, Bangladesh (you can change this)
                map = L.map('map').setView([23.8103, 90.4125], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                map.on('click', function(e) {
                    setMarker(e.latlng);
                });
            }
            
            // Invalidate map size to fix rendering issues
            setTimeout(() => {
                map.invalidateSize();
            }, 200);
        });
        
        function setMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, {draggable: true}).addTo(map);
                
                marker.on('dragend', function(e) {
                    var position = marker.getLatLng();
                    $('#latitude').val(position.lat);
                    $('#longitude').val(position.lng);
                });
            }
            
            $('#latitude').val(latlng.lat);
            $('#longitude').val(latlng.lng);
        }
        
        // Initialize edit map when edit modal opens
        $(document).on('click', '.btn-edit', function() {
            const data = $(this).data('data');
            
            // Loop through data object
            $.each(data, function(key, value) {
                $('#editModal input[name="' + key + '"]').val(value);
                $('#editModal select[name="' + key + '"] option[value="' + value + '"]').prop('selected', true);
            });
            
            $("#editFrom").attr("action", $(this).data('action'));
            
            // Initialize edit map after modal is shown
            $('#editModal').on('shown.bs.modal', function () {
                if (!editMap) {
                    // Default to Dhaka, Bangladesh (you can change this)
                    editMap = L.map('editMap').setView([23.8103, 90.4125], 13);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(editMap);
                    
                    editMap.on('click', function(e) {
                        setEditMarker(e.latlng);
                    });
                }
                
                // Set existing location if available
                if (data.latitude && data.longitude) {
                    setEditMarker({lat: parseFloat(data.latitude), lng: parseFloat(data.longitude)});
                    editMap.setView({lat: parseFloat(data.latitude), lng: parseFloat(data.longitude)}, 15);
                }
                
                // Invalidate map size to fix rendering issues
                setTimeout(() => {
                    editMap.invalidateSize();
                }, 200);
            });
        });
        
        function setEditMarker(latlng) {
            if (editMarker) {
                editMarker.setLatLng(latlng);
            } else {
                editMarker = L.marker(latlng, {draggable: true}).addTo(editMap);
                
                editMarker.on('dragend', function(e) {
                    var position = editMarker.getLatLng();
                    $('#edit_latitude').val(position.lat);
                    $('#edit_longitude').val(position.lng);
                });
            }
            
            $('#edit_latitude').val(latlng.lat);
            $('#edit_longitude').val(latlng.lng);
        }
        
        // Clear edit map when modal is closed
        $('#editModal').on('hidden.bs.modal', function () {
            if (editMap) {
                editMap.remove();
                editMap = null;
                editMarker = null;
            }
        });
    </script>
@endsection
