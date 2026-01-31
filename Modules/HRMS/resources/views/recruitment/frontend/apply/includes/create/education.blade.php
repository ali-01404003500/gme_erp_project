<div class="row">
    <div class="col-sm-12">
        <h3 class="header smaller lighter blue">Educational Qualification</h3>
        <table id="education_table" class="table table-bordered edu1">
            <thead>
                <tr>
                    <td width="5%">SL</td>
                    <td>Examination</td>
                    <th>CGPA / Number</th>
                    <td>Passing Year</td>
                    <td>Institute / Board</td>
                    {{-- <td colspan="2">File</td> --}}
                </tr>
            </thead>
            <tbody>

                @if (old('examination'))
                    @foreach (old('examination') as $key => $value)
                        <tr id="education">
                            <td style="text-align: center;">
                                {{ $key + 1 }}
                            </td>
                            <td>
                                <input type="text"
                                    value="{{ old('examination')[$key] ?? '' }}"
                                    name="examination[]" class="form-control input-sm" />
                            </td>
                            <td>
                                <input type="text" value="{{ old('result')[$key] ?? '' }}"
                                    name="result[]" class="form-control input-sm" />
                            </td>
                            <td>
                                <input type="text"
                                    value="{{ old('passing_year')[$key] ?? '' }}"
                                    name="passing_year[]" class="form-control input-sm" />
                            </td>
                            <td>
                                <input type="text" value="{{ old('institute')[$key] ?? '' }}"
                                    name="institute[]" class="form-control input-sm" />
                            </td>
                            
                            <td>
                                <button type="button" class="btn btn-danger disabled btn-xs" id="remove_row" disabled
                                    onclick="removeRow(this)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="text-align: center;">
                            1
                        </td>
                        <td>
                            <input type="text" value="" name="examination[]"
                                class="form-control input-sm" />
                        </td>
                        <td>
                            <input type="text" value="" name="result[]"
                                class="form-control input-sm" />
                        </td>
                        <td>
                            <input type="text" value="" name="passing_year[]"
                                class="form-control input-sm" />
                        </td>
                        <td>
                            <input type="text" value="" name="institute[]"
                                class="form-control input-sm" />
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger disabled btn-xs" id="remove_row" disabled
                                onclick="removeRow(this)">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                @endif

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-info btn-sm" id="add_row">
                                <i class="fa fa-plus"></i> Add</button>
                        </div>
                        
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>