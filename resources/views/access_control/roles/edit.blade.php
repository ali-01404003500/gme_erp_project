{{-- Company: Opzo Erp Helth. --}}{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@extends('layout.app')

@section('title', 'Update Role')

@section('page-header')
    <div class="page-title">Update Role</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="#">Update Role</a></li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 page-title-wrapper">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
                <h2 class="inner-page-title pt-1">Update Role</h2>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Whoops!</strong> {{ $errors->first() }}
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <form action="{{ route('access_control.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="widget mb-4">
                            <div class="row mb-3">
                                <label class="col-lg-2 col-form-label " for="name">Role Name:</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter a Role Name" value="{{ old('name', $role->name) }}">
                                </div>
                            </div>
                        </div>
                        <h3>Permission access</h3>
                        <div class="card mb-4">
                            <div class="card-body ">
                                @foreach ($permissionMasters as $master)
                                    @if(hasPermission($master->key.'.*'))
                                        <div class="widget mb-4">
                                            <div class="accordion mb-2 master-accordion" id="master-accordion-{{ $master->id }}">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="master-heading-{{ $master->id }}">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#master-collapse-{{ $master->id }}" aria-expanded="false"
                                                            aria-controls="master-collapse-{{ $master->id }}">
                                                            <input class="form-check-input master-check" id="master-check-{{ $master->id }}" type="checkbox"
                                                                @if ($role->permissions->contains($master->id)) checked @endif>
                                                            <label class="form-check-label ms-2" for="master-check-{{ $master->id }}">
                                                                {{ $master->title }}
                                                            </label>
                                                        </button>
                                                    </h2>
                                                    <div id="master-collapse-{{ $master->id }}" class="accordion-collapse collapse"
                                                        aria-labelledby="master-heading-{{ $master->id }}" data-bs-parent=".widget">
                                                        <div class="accordion-body">
                                                            <div class="accordion mb-2" id="accordion-{{ $master->id }}">
                                                                @if($master->permissions->count() && hasPermission($master->key.'.*'))
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-{{ $master->id }}">
                                                                            <button class="accordion-button collapsed" type="button"
                                                                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $master->id }}"
                                                                                aria-expanded="false" aria-controls="collapse-{{ $master->id }}">
                                                                                {{ $master->title }}
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapse-{{ $master->id }}" class="accordion-collapse collapse"
                                                                            aria-labelledby="heading{{ $master->id }}"
                                                                            data-bs-parent="#accordion-{{ $master->id }}">
                                                                            <div class="accordion-body">
                                                                                <div class="form-check form-check-inline" title="all">
                                                                                    <input class="form-check-input all" id="all-{{ $master->id }}" type="checkbox">
                                                                                    <label class="form-check-label" for="all-{{ $master->id }}">All</label>
                                                                                </div>
                                                                                @foreach ($master->permissions ?? [] as $key => $value)

                                                                                    @if( hasPermission($value->slug) )
                                                                                        <div class="form-check form-check-inline"
                                                                                            title="{{ $value->description }} ({{ $value->slug }})">
                                                                                            <input class="form-check-input" name="permitted[{{ $value->id }}][]"
                                                                                                type="checkbox" @if ($role->permissions->contains($value->id)) checked @endif
                                                                                                id="{{ $value->id }}" value="{{ $value->id }}">
                                                                                            <label class="form-check-label" for="{{ $value->id }}">{{ $value->name }}</label>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @foreach ($master->subMasters as $subMaster)
                                                                    {{-- @dd($subMaster) --}}
                                                                    @if ($subMaster->permissions->count() && hasPermission($subMaster->key.'.*'))
                                                                    {{-- @dd(hasPermission($subMaster->key.'.*'), $subMaster->key) --}}

                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header" id="heading-{{ $subMaster->id }}">
                                                                                <button class="accordion-button collapsed" type="button"
                                                                                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ $subMaster->id }}"
                                                                                    aria-expanded="false" aria-controls="collapse-{{ $subMaster->id }}">
                                                                                    <label class="form-check-label ms-2"
                                                                                        for="sub-master-check-{{ $subMaster->id }}">{{ $subMaster->title }}</label>
                                                                                    <span style="margin-left: auto;">
                                                                                        <p style="padding:0px; margin:0px;">
                                                                                            <i class="fa fa-info-circle p-1" aria-hidden="true"></i>
                                                                                            {{ $subMaster->description }}
                                                                                        </p>
                                                                                    </span>
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapse-{{ $subMaster->id }}" class="accordion-collapse collapse"
                                                                                aria-labelledby="heading{{ $subMaster->id }}"
                                                                                data-bs-parent="#accordion-{{ $master->id }}">
                                                                                <div class="accordion-body">
                                                                                    <div class="form-check form-check-inline" title="all">
                                                                                        <input class="form-check-input all" id="all-{{ $subMaster->id }}"
                                                                                            type="checkbox">
                                                                                        <label class="form-check-label" for="all-{{ $subMaster->id }}">All</label>
                                                                                    </div>
                                                                                    @foreach ($subMaster->permissions ?? [] as $permission)
                                                                                        @if( hasPermission($permission->slug) )
                                                                                            <div class="form-check form-check-inline"
                                                                                                title="{{ $permission->description }} ({{ $permission->slug }})">
                                                                                                <input class="form-check-input" name="permitted[{{ $permission->id }}][]"
                                                                                                    type="checkbox" @if ($role->permissions->contains($permission->id)) checked @endif
                                                                                                    id="{{ $permission->id }}" value="{{ $permission->id }}">
                                                                                                <label class="form-check-label" for="{{ $permission->id }}">
                                                                                                    {{ $permission->name }}
                                                                                                </label>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @foreach ($subMaster->subMasters as $nestedSubMaster)
                                                                        
                                                                        @if ($nestedSubMaster->permissions->count() && hasPermission($nestedSubMaster->key.'.*'))
                                                                            <div class="accordion-item">
                                                                                <h2 class="accordion-header" id="heading-{{ $nestedSubMaster->id }}">
                                                                                    <button class="accordion-button collapsed" type="button"
                                                                                        data-bs-toggle="collapse"
                                                                                        data-bs-target="#collapse-{{ $nestedSubMaster->id }}"
                                                                                        aria-expanded="false"
                                                                                        aria-controls="collapse-{{ $nestedSubMaster->id }}">
                                                                                        {{ $nestedSubMaster->title }}
                                                                                        <span style="margin-left: auto;">
                                                                                            <p style="padding:0px; margin:0px;">
                                                                                                <i class="fa fa-info-circle p-1" aria-hidden="true"></i>
                                                                                                {{ $nestedSubMaster->description }}
                                                                                            </p>
                                                                                        </span>
                                                                                    </button>
                                                                                </h2>
                                                                                <div id="collapse-{{ $nestedSubMaster->id }}" class="accordion-collapse collapse"
                                                                                    aria-labelledby="heading{{ $nestedSubMaster->id }}"
                                                                                    data-bs-parent="#accordion-{{ $subMaster->id }}">
                                                                                    <div class="accordion-body">
                                                                                        <div class="form-check form-check-inline" title="all">
                                                                                            <input class="form-check-input all" id="all-{{ $nestedSubMaster->id }}"
                                                                                                type="checkbox">
                                                                                            <label class="form-check-label"
                                                                                                for="all-{{ $nestedSubMaster->id }}">All</label>
                                                                                        </div>
                                                                                        @foreach ($nestedSubMaster->permissions ?? [] as $permission)
                                                                                            @if( hasPermission($permission->slug) )
                                                                                                <div class="form-check form-check-inline"
                                                                                                    title="{{ $permission->description }} ({{ $permission->slug }})">
                                                                                                    <input class="form-check-input" name="permitted[{{ $permission->id }}][]"
                                                                                                        type="checkbox" @if ($role->permissions->contains($permission->id)) checked @endif
                                                                                                        id="{{ $permission->id }}" value="{{ $permission->id }}">
                                                                                                    <label class="form-check-label" for="{{ $permission->id }}">
                                                                                                        {{ $permission->name }}
                                                                                                    </label>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            
                            
                                <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Update Role</button>
                                </div>
                            </div>
                        </div>
                        

                       
                    </form>
                </div>
            </div>
        </div>
    </div>


@endSection

@section("page_scripts")
{{-- <script>
    $(document).ready(function() {
        $('.all').click(function() {
            if ($(this).is(':checked')) {
                $(this).parents('.accordion-item').find('.form-check-input').prop('checked', true);
            }else{
                $(this).parents('.accordion-item').find('.form-check-input').prop('checked', false);
            }
        });

        //if all is checked then check all
        $('.form-check-input').not('.all').click(function() {
            if ($(this).parents('.accordion-item').find(".form-check-input:checked").not(".all").length == $(this).parents('.accordion-item').find(".form-check-input").not(".all").length) {
                $(this).parents('.accordion-item').find('.all').prop('checked', true);
            }else{
                $(this).parents('.accordion-item').find('.all').prop('checked', false);
            }
        });


        $('.form-check-input').each(function() {
            if ($(this).parents('.accordion-item').find(".form-check-input:checked").not(".all").length == $(this).parents('.accordion-item').find(".form-check-input").not(".all").length) {
                $(this).parents('.accordion-item').find('.all').prop('checked', true);
            }else{
                $(this).parents('.accordion-item').find('.all').prop('checked', false);
            }
        })
    });
</script> --}}

<script>
    $(document).ready(function () {
        // Handle master checkbox toggle
        $('.master-check').on('change', function () {
            const isChecked = $(this).is(':checked');
            const masterAccordion = $(this).closest('.accordion-item');
            const allInputs = masterAccordion.find('.form-check-input').not('.master-check');

            // Check/uncheck all permissions under the master when the master checkbox is toggled
            allInputs.prop('checked', isChecked).trigger('change');
            masterAccordion.find('.all').prop('checked', isChecked);
        });
        

        $('.form-check-input').not('.all').click(function() {
            console.log($(this).parents('.accordion-item').first());
            const parentAccordion = $(this).parents('.accordion-item').first();
            if (parentAccordion.find(".form-check-input:checked").not(".all").length == parentAccordion.find(".form-check-input").not(".all").length) {
                parentAccordion.find('.all').prop('checked', true);
            }else{
                parentAccordion.find('.all').prop('checked', false);
            }
        });

        $('.form-check-input').not('.master-check').click(function() {
            const masterAccordion = $(this).parents('.master-accordion');
            console.log(masterAccordion.find(".form-check-input:checked").not(".master-check"));

            if (masterAccordion.find(".form-check-input:checked").not(".master-check").length == masterAccordion.find(".form-check-input").not(".master-check").length) {
                masterAccordion.find('.master-check').prop('checked', true);
            }else{
                masterAccordion.find('.master-check').prop('checked', false);
            }
        });
     
        // Handle cascading behavior for the "All" checkbox under each section
        $('.form-check-input.all').on('change', function () {
            const isChecked = $(this).is(':checked');
            const subAccordion = $(this).closest('.accordion-collapse');
            const allInputs = subAccordion.find('.form-check-input').not('.all');

            // Check/uncheck all permissions under the sub-master when "All" checkbox is toggled
            allInputs.prop('checked', isChecked).trigger('change');

            const masterParentAccordion = $(this).closest('.master-accordion');
            const masterAllInputs = masterParentAccordion.find('.form-check-input').not('.master-check');


            // Check if all child permissions are checked, and update the master checkbox
            const isMasterChecked = masterAllInputs.filter(':checked').length === masterAllInputs.length;
            
            masterParentAccordion.find('.master-check').prop('checked', isMasterChecked);
        });

 

        // Update master checkbox state when individual permissions are checked/unchecked
        $('.form-check-input').each(function () {
            const parentAccordion = $(this).closest('.accordion-item');
            const allInputs = parentAccordion.find('.form-check-input').not('.all');

            // Check if all child permissions are checked, and update the master checkbox
            const isChecked = allInputs.filter(':checked').length === allInputs.length;
            console.log(isChecked);
            
            parentAccordion.find('.master-check').prop('checked', isChecked);
            parentAccordion.find('.all').prop('checked', isChecked);

            // master
            const masterParentAccordion = parentAccordion.closest('.master-accordion');
            const masterAllInputs = masterParentAccordion.find('.form-check-input').not('.master-check');


            // Check if all child permissions are checked, and update the master checkbox
            const isMasterChecked = masterAllInputs.filter(':checked').length === masterAllInputs.length;
            
            masterParentAccordion.find('.master-check').prop('checked', isMasterChecked);
        });
        
    });
</script>

@endsection
