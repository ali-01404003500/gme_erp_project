
@section('title', 'Customer List')
@section('description', 'Customer List')
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact-breadcrumb">
                    <div class="breadcrumb-main add-contact justify-content-sm-between ">
                        <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                            <div class="d-flex align-items-center add-contact__title justify-content-center me-sm-25">
                                <h4 class="text-capitalize fw-500 breadcrumb-title">{{ trans('menu.customer-view-all') }}
                                </h4>
                                <span class="sub-title ms-sm-25 ps-sm-25"></span>
                            </div>
                            <div class="action-btn mt-sm-0 mt-15">
                                <a href="{{ route('crm.customers.create', app()->getLocale()) }}" class="btn px-20 btn-primary ">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            </div>
                        </div>
                        <div class="breadcrumb-main__wrapper">

                            <form action="/" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                                <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg">
                                <input class="form-control me-sm-2 border-0 box-shadow-none" type="search"
                                    placeholder="Search by Name" aria-label="Search">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-header color-dark fw-500">
                        Customer List
                    </div>
                    <div class="card-body">
                        <div class="userDatatable global-shadow border-light-0 w-100">
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless">
                                    <thead>
                                        <tr class="userDatatable-header">
                                            <th>SL</th>
                                            <th>
                                                <span class="checkbox-text userDatatable-title">Company Name</span>
                                            </th>
                                            <th>
                                                <span class="userDatatable-title">Contact Number</span>
                                            </th>
                                            <th>
                                                <span class="userDatatable-title">Email Address</span>                                                </span>
                                            </th>
                                            <th>
                                                <span class="userDatatable-title">Address</span>
                                            </th>
                                            <th>
                                                <span class="userDatatable-title">Contact Person Name
                                                </span>
                                            </th>
                                            <th>
                                                <span class="userDatatable-title">Owner Name
                                                </span>
                                            </th>
                                            <th>
                                                <span class="userDatatable-title float-end">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($customers) == 0)
                                            <tr>
                                                <td colspan="7">
                                                    <p class="text-center">No Customer Found !</p>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($customers as $customer)
                                                @php
                                                    $has_profile_picture = ! empty( $customer->profile_picture );
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{$loop->iteration}}
                                                    </td>
                                                    <td> 
                                                        <div class="userDatatable-inline-title">
                                                            <a class="text-dark fw-500" href="{{ route('crm.customers.show', $customer->id) }}"><h6>{{ $customer->company_name }}</h6></i></a>
                                                            {{-- <a href="{{ route('crm.customers.show', $customer->id) }}" class="text-dark fw-500">
                                                                
                                                            </a> --}}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="userDatatable-content">
                                                            {{ $customer->phone }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="userDatatable-content">
                                                            {{ $customer->email }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="userDatatable-content">
                                                            {{ $customer->address }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="userDatatable-content">
                                                            {{ $customer->contact_person_name }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="userDatatable-content d-inline-block">
                                                            <span>
                                                                {{ $customer->owner_name }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                                <a class="btn btn-outline-warning" href="{{ route('crm.customers.edit', $customer->id) }}"><i
                                                                        class="far fa-edit"></i></a>
                        
                                                                <button type="button" data-action="{{ route('crm.customers.destroy', $customer->id) }}"
                                                                    class="btn btn-outline-danger delete-confirm"><i
                                                                        class="far fa-trash-alt"></i></button>
                        
                                                                <a class="btn btn-outline-primary" href="{{ route('crm.customers.show', $customer->id) }}"><i class="fas fa-eye"></i></a>
                                                        </div>
            
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="d-none">
                                    <form class="delete-form" action="" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="pagination-container d-flex justify-content-end pt-25">
                            {{ $customers->links( 'pagination::bootstrap-5' ) }}

                            <ul class="dm-pagination d-flex">
                                <li class="dm-pagination__item">
                                    <div class="paging-option">
                                        <select name="page-number" class="page-selection" onchange="updatePagination( event )">
                                            <option value="20" {{ 20 == $customers->perPage() ? 'selected' : '' }}>20/page</option>
                                            <option value="40" {{ 40 == $customers->perPage() ? 'selected' : '' }}>40/page</option>
                                            <option value="60" {{ 60 == $customers->perPage() ? 'selected' : '' }}>60/page</option>
                                        </select>
                                        <a href="/pagination-per-page/20" class="d-none per-page-pagination"></a>
                                    </div>
                                </li>
                            </ul>

                            <script>
                                function updatePagination( event ) {
                                    var per_page = event.target.value;

                                    const per_page_link = document.querySelector( '.per-page-pagination' );
                                    per_page_link.setAttribute( 'href', '/pagination-per-page/' + per_page  );

                                    per_page_link.click();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
