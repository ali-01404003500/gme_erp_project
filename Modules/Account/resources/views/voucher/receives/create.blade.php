@section('title', 'Receive Voucher')
@section('description', 'Receive Voucher')
@extends('layout.app')
@section('page-head')

@endsection
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Receive Voucher') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            @if (hasPermission('account.voucher-receives.index'))
                                <a href="{{ route('account.voucher-receives.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Receive Voucher') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="form" action="{{ route('account.voucher-receives.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
            
                                <input type="hidden" name="voucher_type" value="Receive">
        
                                <div class="row mt-1">
            
                                    <div class="col-sm-12 px-3">
            
            
                                        <!-- Filter -->
                                        <div class="row mt-4 mb-4">
                                            <!-- Reference -->
                                            <div class="col-sm-5 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Reference
                                                    </span>
                                                    <input name="reference" value="{{ old('reference') }}" class="form-control"
                                                        type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 my-1">
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        Date
                                                    </span>
                                                    <input name="date" class="form-control date-picker text-center" type="text"
                                                        value="{{ old('date', date('Y-m-d')) }}" data-date-format="yyyy-mm-dd">
            
                                                    @error('date')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Item Detail -->
                                        <div class="row mt-2">
                                            <div class="col-sm-12">
                                                <table id="myTable" class="table table-bordered order-list">
            
                                                    <thead>
                                                        <tr>
                                                            <th width="50px;" class="text-center">SL.</th>
                                                            <th>Account<span class="text-danger">*</span></th>
                                                            <th class="text-right" width="150px;">Debit</th>
                                                            <th class="text-right" width="150px;">Credit</th>
                                                            <th width="50px;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (old('account_ids'))
                                                            @foreach (old('account_ids') as $key => $value)
                                                                <tr>
                                                                    <td class="count text-center"></td>
                                                                    <td>
                                                                        <div class="col-sm-12">
                                                                            <select required="required" name="account_ids[]"
                                                                                id="account_id" class="form-control tom-select required"
                                                                                data-placeholder="- Select Account -">
                                                                                <option></option>
                                                                                @foreach ($accounts as $id => $account)
                                                                                    <option value="{{ $account->id }}"
                                                                                    {{ $account->id == $value ? 'selected' : '' }}>
                                                                                    {{ $account->account_with_group }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('account_ids')
                                                                                <span class="text-danger"> {{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input name="debit[]" value="{{ old('debit')[$key] }}"
                                                                            type="text" onclick="enableMe(this)"
                                                                            onkeyup="disabledReverse('input-credit', this)"
                                                                            class="form-control only-number text-right input-debit calculate-total input-sm" />
                                                                        @error('debit')
                                                                            <span class="text-danger"> {{ $message }}</span>
                                                                        @enderror
                                                                    </td>
                                                                    <td>
                                                                        <input name="credit[]" value="{{ old('credit')[$key] }}"
                                                                            type="text" onclick="enableMe(this)"
                                                                            onkeyup="disabledReverse('input-debit', this)"
                                                                            class="form-control only-number text-right input-credit calculate-total input-sm" />
                                                                        @error('credit')
                                                                            <span class="text-danger"> {{ $message }}</span>
                                                                        @enderror
                                                                    </td>
                                                                    <td class="text-center"><a class="btn btn-sm btn-danger"
                                                                            disabled="disabled"><i class="fa fa-trash"></i></a></td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td class="count text-center"></td>
                                                                <td>
                                                                    <div class="col-sm-12">
                                                                        <select required="required" name="account_ids[]"
                                                                            id="account_ids" class="form-control tom-select required"
                                                                            data-placeholder="- Select Account -">
                                                                            <option></option>
            
                                                                            @foreach ($accounts as $id => $value)
                                                                                <option value="{{ $value->id }}">
                                                                                    {{ $value->account_with_group}}</option>
                                                                            @endforeach
                                                                        </select>
            
            
                                                                        @error('account_ids')
                                                                            <span class="text-danger"> {{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input name="debit[]" type="text" onclick="enableMe(this)"
                                                                        onkeyup="disabledReverse('input-credit', this)"
                                                                        class="form-control only-number text-right input-debit calculate-total input-sm" />
                                                                    @error('debit')
                                                                        <span class="text-danger"> {{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input name="credit[]" type="text" onclick="enableMe(this)"
                                                                        onkeyup="disabledReverse('input-debit', this)"
                                                                        class="form-control only-number text-right input-credit calculate-total input-sm" />
                                                                    @error('credit')
                                                                        <span class="text-danger"> {{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="text-center"><a class="btn btn-sm btn-danger"
                                                                        disabled="disabled"><i class="fa fa-trash"></i></a></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="count text-center"></td>
                                                                <td>
                                                                    <div class="col-sm-12">
                                                                        <select required id="account_ids" name="account_ids[]"
                                                                            class="form-control tom-select required"
                                                                            data-placeholder="- Select Account -">
                                                                            <option value=""></option>
            
                                                                            @foreach ($accounts as $id => $value)
                                                                                <option value="{{ $value->id }}">
                                                                                    {{ $value->account_with_group}}</option>
                                                                            @endforeach
                                                                        </select>
            
            
                                                                        @error('account_ids')
                                                                            <span class="text-danger"> {{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input name="debit[]" type="text" onclick="enableMe(this)"
                                                                        onkeyup="disabledReverse('input-credit', this)"
                                                                        class="form-control only-number text-right calculate-total input-debit input-sm" />
                                                                    @error('debit')
                                                                        <span class="text-danger"> {{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input name="credit[]" type="text" onclick="enableMe(this)"
                                                                        onkeyup="disabledReverse('input-debit', this)"
                                                                        class="form-control only-number text-right input-credit calculate-total input-sm" />
                                                                    @error('credit')
                                                                        <span class="text-danger"> {{ $message }}</span>
                                                                    @enderror
                                                                </td>
                                                                <td class="text-center"><a class="btn btn-sm btn-danger"
                                                                        disabled="disabled"><i class="fa fa-trash"></i></a></td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
            
            
            
                                                    <!-- Table Footer -->
                                                    <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-right item-serial">Total</td>
                                                            <td>
                                                                <input name="total_debit" readonly value="{{ old('total_debit') }}"
                                                                    disabled class="total-debit text-right form-control"
                                                                    style="border: none;">
                                                            </td>
                                                            <td>
                                                                <input name="total_credit" readonly disabled
                                                                    value="{{ old('total_credit') }}"
                                                                    class="total-credit text-right form-control" style="border: none;">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-success" id="addrow">
                                                                    +
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
            
            
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="input-group " style="width: 100%!important; float: left; ">
                                                    <span class="input-group-text">Narration/Description</span>
                                                    <input type="text" required class="form-control" name="description"
                                                        value="{{ old('description') }}" placeholder="Narration / Description">
            
                                                    @error('description')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <input id="front-image" type="file" accept="image/*"
                                                        name="attachment" class="file-control form-control"
                                                        data-preview-element="front-image-preview">
                                                </div>
                                            </div>
                                           
                                            <!-- Action -->
                                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                                <div class="btn-group">
                                                    <button type="button" id="draft" class="btn btn-sm btn-primary save-btn" disabled>
                                                        <i class="fa fa-file"></i>
                                                        Draft
                                                        <input type="hidden" name="draft" class="draft-value" value="0">
                                                    </button>
                                                    @if(hasPermission("account.voucher-receives.approve"))
<button type="submit" class="btn btn-sm btn-success save-btn" disabled>
                                                        <i class="fa fa fa-save"></i>
                                                        Save & Approve
                                                    </button>
@endif
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
    const saveButton = $('.save-btn')
    const inputDebit = $('.input-debit')
    const inputCredit = $('.input-credit')

    const rowItem = `<tr>
                        <td class="count"></td>
                        <td>
                            <div class="col-sm-12">
                                <select id="account_ids" name="account_ids[]" class="form-control to-select" data-placeholder="- Select Account -">
                                    <option value=""></option>
                                    @foreach ($accounts as $id => $value)
                                        <option value="{{ $value->id }}">{{ $value->account_with_group}}</option>
                                    @endforeach
                                </select>
                                @error('account_ids')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <input name="debit[]" type="text" onclick="enableMe(this)" onkeyup="disabledReverse('input-credit', this)" class="form-control only-number text-right calculate-total input-debit input-sm" />
                            @error('debit')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </td>
                        <td>
                            <input name="credit[]" type="text" onclick="enableMe(this)" onkeyup="disabledReverse('input-debit', this)" class="form-control only-number text-right input-credit calculate-total input-sm" />
                            @error('credit')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </td>
                        <td><a class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                    </tr>`






    $(document).on("keyup", ".calculate-total", function() {
        calculateAmount()
    });

    function calculateAmount() {

        var debitTotal = 0;

        $(".input-debit").each(function() {
            debitTotal += Number($(this).val());
        });

        var creditTotal = 0;
        $(".input-credit").each(function() {
            creditTotal += Number($(this).val());
        });

        $(".total-debit").val(debitTotal);
        $(".total-credit").val(creditTotal);

        if (debitTotal === creditTotal && debitTotal > 0) {
            saveButton.attr('disabled', false)
        } else {
            saveButton.attr('disabled', true)
        }
    }
    function disabledReverse($class_name, object) {
        let disableItem = $(object).closest('tr').find('.' + $class_name)
        disableItem.attr('readonly', true).val('0')
    }

    function enableMe(object) {
        $(object).attr('readonly', false)
    }
    $(document).ready(function() {
        let rowCount = 2; // Start with 2 default rows

        // Function to update SL numbers
        function updateSLNumbers() {
            $(".count").each(function(index) {
                $(this).text(index + 1); // Set SL number based on index
            });
        }

        // Add row functionality
        $("#addrow").on("click", function() {
            $("table.order-list").append(rowItem);
            rowCount++; // Increment row count
            updateSLNumbers(); // Update SL numbers after adding a row
            $('.to-select').each(function() {
                new TomSelect(this, {});
            });
        });

        // Delete row functionality
        $("table.order-list").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            updateSLNumbers(); // Update SL numbers after removing a row
            calculateAmount(); // Recalculate totals
        });

        // Initial row setup
        updateSLNumbers(); // Update SL numbers for initial rows
        calculateAmount(); // Calculate initial totals
    });

    
    $("#draft").click(function() {

    $(".draft-value").val(1);

    $('#form').submit();
    });
</script>
@endsection
