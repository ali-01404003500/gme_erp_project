@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-items-center user-member__title mb-30 mt-30">
                    <h4 class="text-capitalize">{{ trans('customer shipping address create') }}</h4>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('crm.customer-shippings.store', app()->getLocale()) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-25">
                                        <label for="customer_name" class="color-dark fs-14 fw-500 align-center">Customer Name</label>
                                        <select class="form-control ih-medium ip-gray radius-xs b-light px-15" name="customer_id" id="customer_id">
                                           <option value="">Select Customer</option>
                                            @foreach ($customers as  $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->company_name }} - {{ $customer->address}}</option>
                                            @endforeach
                                       </select>
                                        @if ($errors->has('customer_id'))
                                            <p class="text-danger">{{ $errors->first('customer_id') }}</p>
                                        @endif
                                    </div>

                                    <div class="form-group mb-25">
                                        <label for="division" class="color-dark fs-14 fw-500 align-center">Division</label>
                                        <select name="division_id" id="division_id" class="form-control ih-medium ip-gray radius-xs b-light px-15">
                                            <option value="">Select Division</option>
                                            <option value="1">Dhaka</option>
                                            <option value="2">Chittagong</option>
                                            <option value="3">Rajshahi</option>
                                            <option value="4">Khulna</option>
                                            <option value="5">Barisal</option>
                                            <option value="6">Sylhet</option>
                                            <option value="7">Rangpur</option>
                                            {{-- @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                            @endforeach --}}
                                        </select>
                                        @if ($errors->has('division'))
                                            <p class="text-danger">{{ $errors->first('division') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-25">
                                        <label for="district"
                                            class="color-dark fs-14 fw-500 align-center">District</label>
                                        <select name="district_id" id="district_id" class="form-control ih-medium ip-gray radius-xs b-light px-15">
                                            <option value="">Select District</option>
                                            <option value="1">Dhaka</option>
                                            <option value="2">Chittagong</option>
                                            <option value="3">Rajshahi</option>
                                            <option value="4">Khulna</option>
                                            <option value="5">Barisal</option>
                                            <option value="6">Sylhet</option>
                                            <option value="7">Rangpur</option>
                                        </select>
                                            {{-- @foreach ($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endforeach --}}
                                            
                                        @if ($errors->has('district'))
                                            <p class="text-danger">{{ $errors->first('district') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-25">
                                        <label for="email"
                                            class="color-dark fs-14 fw-500 align-center">Shipping Address</label>
                                            <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" name="address" style="height: 100px;" id="address" placeholder="Shipping Address"></textarea>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group mb-25">
                                        <label for="contact_person_name" class="color-dark fs-14 fw-500 align-center">Contact Person Name</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="contact_person_name" id="name" placeholder="Contact Person Name">
                                        @if ($errors->has('contact_person_name'))
                                            <p class="text-danger">{{ $errors->first('contact_person_name') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-25">
                                        <label for="contact_person_mobile" class="color-dark fs-14 fw-500 align-center">Contact Person Phone</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="contact_person_mobile" id="contact_person_mobile" placeholder="Contact Person Phone">
                                        @if ($errors->has('contact_person_mobile'))
                                            <p class="text-danger">{{ $errors->first('contact_person_mobile') }}</p>
                                        @endif
                                    </div>
                                    <div class="form-group mb-25">
                                        <label for="contact_person_email" class="color-dark fs-14 fw-500 align-center">Contact Person Email</label>
                                        <input type="text" class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                            name="contact_person_email" id="contact_person_email" placeholder="Contact Person Email">
                                        @if ($errors->has('contact_person_email'))
                                            <p class="text-danger">{{ $errors->first('contact_person_email') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>
                            

                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <button type="submit"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>
@endSection
