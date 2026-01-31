{{-- @extends('layouts.master') --}}
@extends('HRMS::recruitment.frontend.layout.master')

@section('title', 'Available Jobs')

@section('page-head')
    <i class="fa fa-tachometer"></i> Available Jobs

    
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset("/assets/plugins/toastr/toastr.min.css")}}">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
        }

        .card {
            background: rgb(240 255 255) !important;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .card:hover {
            box-shadow: 0 0 11px rgba(190, 184, 184, 0.2);
            color: #000;
        }

        p {
            font-size: 14px;
        }

        .nav-tabs {
            float: right;
            border: 1px solid transparent;
        }

        .tab-manu h4 {
            float: left;
            margin-bottom: 14px;

            margin-top: 10px;
        }
        .job-post {
            margin-bottom: 15px;
            background-color: #fff;
            padding: 15px 30px;
        }
        .panel-footer{
            background: #faffff;
        }

    </style>
@endsection


@section('content')


       
    </div>
    <div class="row">
       
        <div class="container">

            <div class="job-post">
                <div class="row mb-4">
                    <div class="section-title tab-manu">
                        <h2 style="margin-left: 24px">OnGoing Jobs</h2>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-sm-12">
                        @forelse ($jobs as $job)
                            <div class="card  mb-4">
                                <div class="card-body " style="background-color:beige; border: 1px solid #000;">
                                    <div class="col-sm-12">
                                        <p><a class="btn btn-outline-primary" href="{{ route('carrier.show',  $job->id.'-'.Str::slug($job->title)) }}">{{ $job->title }}</a>
                                        </p>
                                        <p><i class="fa fa-map-marker light-blue bigger-110"></i>
                                            {{ $job->location }}</p>
                                        <p><i class="fa fa-book light-orange bigger-110"></i>
                                            {!! $job->educational_requirement !!}
                                        </p>
                                        <p><i class="fa fa-universal-access bigger-110"></i>
                                            {!! $job->experience ?? 'N/A' !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="card-footer" style="border: 1px solid #000;">
                                    <div class="row">
                                        <div class="col-sm-6" style="vertical-align: middle">
                                            <i class="fa fa-calendar"></i> Deadline At:
                                            {{ $job->deadline_at }}
                                        </div>
                                        <div class="col-sm-6 text-right">
                                             <a href="{{ route('carrier.apply', $job->id) }}" target="_blank"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-telegram"></i> Apply Now
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        @empty
                            @noTableRecordsFound
                        @endforelse
                    </div>
                </div>

            </div>



            <div class="widget-bosx" hidden>

                <div class="widget-headers">
                    <h2 class="widget-titles">
                        <strong class="text-bold">{{ $jobs->total() }}</strong> Available Jobs
                    </h2>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('page_scripts')

  <script src="{{ asset('/assets/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        @if (session()->has('success'))
            $(document).ready(function() {
                toastr.success("{{ session('success') }}");
            })
        @endif
    </script>
@endsection
