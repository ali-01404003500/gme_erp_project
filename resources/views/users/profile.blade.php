@section('title',"Profile")
@section('description',"My Profiles")
@extends('layout.app')
@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap mb-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.social-profile-setting') }}</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.social-profile') }}</li>
                            </ol>
                        </nav>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="row">
            <x-error-alart />
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="account-profile border-bottom pt-25 px-25 pb-0 flex-column d-flex align-items-center ">
                                    <div class="ap-img mb-20 pro_img_wrapper">
                                        <label for="file-upload">
                                            <img class="ap-img__main rounded-circle wh-120" src="{{optional(Auth::user()->employee)->photograph??asset('assets/img/author-nav.jpg') }}" alt="profile">
                                            <span class="cross" id="remove_pro_pic" data-bs-toggle="modal" data-bs-target="#uploadAvatarModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg replaced-svg"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="ap-nameAddress pb-3 text-center">
                                        <h5 class="ap-nameAddress__title"> <i class="fa fa-user-alt me-4"></i> {{Auth::user()->name}}</h5>
                                        <p class="ap-nameAddress__subTitle fs-14 m-0"> <i class="fa fa-envelope me-4"></i> {{Auth::user()->email}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                
                                    <div class="nav-wrapper mb-4">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#personal-details" data-bs-toggle="tab">Personal Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#security" data-bs-toggle="tab">Security</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#data-privacy" data-bs-toggle="tab">Data & Privacy</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#payments-subscriptions" data-bs-toggle="tab">Payments & Subscriptions</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personal-details">
                                            <div class="tab-pane active" id="personal-details">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Field</th>
                                                            <th>Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(Auth::user()->employee)
                                                            <tr>
                                                                <td>Employee ID</td>
                                                                <td>{{ Auth::user()->employee->id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Full Name</td>
                                                                <td>{{ Auth::user()->employee->full_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>{{ Auth::user()->employee->email_address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Photograph</td>
                                                                <td><img src="{{ Auth::user()->employee->photograph ?? asset('assets/img/author-nav.jpg') }}" alt="User Avatar" width="50" height="50"></td>
                                                            </tr>
                                                            @if(Auth::user()->employee->present_address)
                                                                <tr>
                                                                    <td>Present Address</td>
                                                                    <td>{{ Auth::user()->employee->present_address }}</td>
                                                                </tr>
                                                            @endif
                                                            @if(Auth::user()->employee->personal_mobile)
                                                                <tr>
                                                                    <td>Personal Mobile</td>
                                                                    <td>{{ Auth::user()->employee->personal_mobile }}</td>
                                                                </tr>
                                                            @endif
                                                            @if(Auth::user()->employee->department)
                                                                <tr>
                                                                    <td>Department</td>
                                                                    <td>{{ Auth::user()->employee->department }}</td>
                                                                </tr>
                                                            @endif
                                                            @if(Auth::user()->employee->designation)
                                                                <tr>
                                                                    <td>Designation</td>
                                                                    <td>{{ Auth::user()->employee->designation }}</td>
                                                                </tr>
                                                            @endif
                                                            @if(Auth::user()->employee->date_of_birth)
                                                                <tr>
                                                                    <td>Date of Birth</td>
                                                                    <td>{{ Auth::user()->employee->date_of_birth }}</td>
                                                                </tr>
                                                            @endif
                                                            @if(Auth::user()->employee->religion)
                                                                <tr>
                                                                    <td>Religion</td>
                                                                    <td>{{ Auth::user()->employee->religion }}</td>
                                                                </tr>
                                                            @endif
                                                            @if(Auth::user()->employee->marital_status)
                                                                <tr>
                                                                    <td>Marital Status</td>
                                                                    <td>{{ Auth::user()->employee->marital_status }}</td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="security">
                                            <!-- Security content -->
                                            <div class="row">
                                                <h4 class="text-center">Click here to update password</h4>
                                                <div class="col-md-12 d-flex justify-content-center my-2">
                                                    
                                                    <button class="btn btn-transparent-dangers" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="data-privacy">
                                            <!-- Data & Privacy content -->
                                            <div class="tab-pane" id="data-privacy">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h5>Data & Privacy Settings</h5>
                                                        <p>Manage your data and privacy settings here.</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                   
                                                </div>
                                               
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="payments-subscriptions">
                                            <!-- Payments & Subscriptions content -->
                                            <h3 class="text-center">You are using a enterprise plan</h3>
                                            
                                            <div class="d-flex justify-content-center my-4">
                                                <a href="#" class="btn btn-transparent-success">Upgrade plan</a>
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal for uploading avatar -->
<div class="modal fade" id="uploadAvatarModal" tabindex="-1" role="dialog" aria-labelledby="uploadAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadAvatarModalLabel">Upload Avatar</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg replaced-svg"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="avatarUploadForm" method="POST" enctype="multipart/form-data" action={{route('profile-photograph-upload')}}>
                    @csrf
                    <div class="form-group">
                        <label for="avatar">Choose Avatar</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg replaced-svg"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" method="POST" action="{{route('change-password')}}" >
                    @csrf
                    <div class="form-group">
                        <label for="old_password">Current Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div> 
        </div>
    </div>
</div>



@endsection