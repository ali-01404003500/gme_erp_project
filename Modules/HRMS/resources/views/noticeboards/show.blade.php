@section('title', 'Notice Details')
@section('description', 'Notice Details')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center user-member__title mb-30 mt-50">
                    <h3 class="text-capitalize">{{ trans('Notice Details') }}</h3>
                    <div class="row">
                        {{-- <a href="{{ route('hrm.noticeboards.show', $noticeBoards->id) }}?export=pdf" target="_blank"
                            class="btn btn-primary ml-auto btn-sm" style="margin-right: 5px;">PDF</a> --}}
                        <a href="{{ route('hrm.noticeboards.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                class="fa fa-list"></i> List</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-50" style="padding-left: 10vh; padding-right: 10vh; padding-top: 5vh; padding-bottom: 5vh">
            <div class="row justify-content-center" id="justify-content-center">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


                    <title>Notice Board</title>
                    <style>
                        .header {
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            margin-bottom: 20px;
                        }

                        .title h1 {
                            font-size: 2em;
                            font-weight: bold;
                            margin: 0;
                        }

                        .details {
                            text-align: right;
                            font-size: 0.9em;
                        }

                        .details p {
                            margin: 5px 0;
                        }

                        .details span {
                            font-weight: bold;
                        }

                        .description {
                            border: 1px solid #ddd;
                            padding: 20px;
                            background-color: #f9f9f9;
                            text-align: center;
                        }
                    </style>
                </head>

                <body>
                    <div class="notice-board">
                        <div class="header">
                            <div class="title">
                                <h1>{{ $noticeBoard->title }}</h1>
                            </div>
                            <div class="details">
                                <p>Type: <span>{{ $noticeBoard->title }}</span></p>
                                <p>Publish Date: <span>{{ $noticeBoard->publish_date }}</span></p>
                                <p>Publish Time: <span>{{ $noticeBoard->publish_time }}</span></p>
                                <p>Expire Date: <span>{{ $noticeBoard->expire_date }}</span></p>
                                <p>Status: <span>{{ $noticeBoard->status }}</span></p>
                            </div>
                        </div>
                        <div class="description">
                            <p style="font-size: 20px;">{{ $noticeBoard->description }}</p>
                        </div>
                        <p>Posted By: <span>{{ $noticeBoard->createdBy->name }}</span></p>
                    </div>
                </body>

            </div>
        </div>
    </div>

@endsection
