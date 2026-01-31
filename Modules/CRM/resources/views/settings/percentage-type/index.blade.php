@section('title', 'Percentage Type')
@section('description', 'Percentage Type')
@extends('layout.app')
@section('content')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans(' Percentage type') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('crm.percentage-type.create'))
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Percentage type') }}</h4>
                        </div>

                    </div>
                </div>


                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($percentageTypes as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $item->code }}</td>
                                            <td class="text-center">{{ $item->name }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    <a href={{ $item->id }} class="btn btn-edit  btn-outline-warning"
                                                        data-name="{{ $item->name }}" data-code="{{ $item->code }}"
                                                        data-action="{{ route('crm.percentage-types.update', $item->id) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                        data-action="{{ route('crm.percentage-types.destroy', $item->id) }}"
                                                        class="btn btn-outline-danger delete-confirm"><i
                                                            class="far fa-trash-alt"></i></button>

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach

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

                </div>

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">Add Percentage Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('crm.percentage-types.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Code</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="code" class="form-control" placeholder=" Code ">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-12 col-form-label">Name</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="name" class="form-control" placeholder=" Name *"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="inputError" class="col-sm-3 control-label bolder">
                                            Status</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <div class="radio">
                                                <label>
                                                    <input name="status" type="radio" value="1" class="ace" checked>
                                                    <span class="lbl"> Active</span>
                                                </label>
                                                <label>
                                                    <input name="status" type="radio" value="0" class="ace">
                                                    <span class="lbl"> In active</span>
                                                </label>
                                            </div>

                                            @error('status')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror

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
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label for="code" class="col-sm-12 col-form-label">Code</label>
                            <div class="col-sm-12">
                                <input name="code" id="code" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="name" class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input name="name" id="name" class="form-control" type="text">
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

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                console.log($(this).data('name'));
                $('#name').val($(this).data('name'));
                $('#code').val($(this).data('code'));
                $("#editFrom").attr("action", $(this).data('action'));

            });
        });

        // function edit(element) {
        //     let name = $(element).data('name');
        //     let code = $(element).data('code');
        //     let action = $(element).data('action');
        //     $('#name').val(name);
        //     $('#code').val(code);
        //     $("#editFrom").attr("action", action);
        // }
    </script>
@endsection
