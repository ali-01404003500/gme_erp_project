@section('title', 'Notifications List')
@section('description', 'Notifications List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Notifications') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Notifications') }}</h4>
                </div>

                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('notifications.general-notifications.index') }}" method="get">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="text-center">
                                                <select name="status" class="form-control">
                                                    <option value="2"
                                                        {{ request()->get('status') == 2 ? 'selected' : '' }}>Read</option>
                                                    <option value="1"
                                                        {{ request()->get('status') == '' || request()->get('status') == 1 ? 'selected' : '' }}>
                                                        Unread</option>
                                                </select>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button type="submit" class="btn btn-xs btn-primary"><i
                                                            class="fa fa-search"></i> Filter</button>
                                                    <a href="{{ route('notifications.general-notifications.index') }}"
                                                        class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i>
                                                        Reset</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <!-- Add bulk actions UI if intended -->
                            <div class="bulk-actions d-flex justify-content-start gap-1" style="margin-bottom: -10px;">
                                <button class="btn btn-sm btn-primary bulk-action" data-action="open">Open Selected</button>
                                <button class="btn btn-sm btn-success bulk-action" data-action="mark-read">Mark as
                                    Read</button>
                            </div>

                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $generalNotifications])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="no-content" width="50px"><input type="checkbox" id="select-all"
                                                @if (request()->get('status') == 2) disabled @endif></th>
                                        <th>Sl</th>
                                        <th>Notification Title</th>
                                        <th>Created At</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($generalNotifications as $notification)
                                        <tr>
                                            <td><input type="checkbox" class="notification-checkbox"
                                                    value="{{ $notification->id }} "
                                                    @if ($notification->status == 2) disabled @endif></td>
                                            <td class="text-center">
                                                {{ ($generalNotifications->currentPage() - 1) * $generalNotifications->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $notification->title }}</td>
                                            <td>{{ $notification->created_at->format('d M Y, h:i A') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    <a href="{{ route('notification.action', $notification->id) }}"
                                                        class="btn btn-outline-primary @if ($notification->status == 2) disabled @endif">
                                                        <i
                                                            class="{{ $notification->status == 2 ? 'far fa-envelope-open' : 'far fa-envelope' }}"></i>
                                                    </a>


                                                    @if (hasPermission('notifications.general-notifications.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('notifications.general-notifications.destroy', $notification->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-none">
                                <!-- Note: Ensure the action attribute is dynamically set via JavaScript if intended -->
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            // Select/deselect all checkboxes
            $('#select-all').change(function() {
                $('.notification-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkActions();
            });

            // Individual checkbox change
            $('.notification-checkbox').change(function() {
                toggleBulkActions();
            });

            function toggleBulkActions() {
                if ($('.notification-checkbox:checked').length > 0) {
                    $('.bulk-actions').show();
                } else {
                    $('.bulk-actions').hide();
                }
            }

            // Handle bulk actions
            $('.bulk-action').click(function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                const selectedIds = $('.notification-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (action === 'open') {
                    // Open each selected notification in new tab
                    selectedIds.forEach(id => {
                        window.open("{{ route('notification.action', ':ID') }}".replace(':ID', id),
                            '_blank');
                    });
                }
                location.reload();
            });

            // Handle delete confirmation (assuming delete-confirm uses SweetAlert or similar)
        });
    </script>
@endsection
