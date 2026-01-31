<div class="row">
    <div class="col-sm-12">
      <h3 class="header smaller lighter blue">Experience</h3>
      <table id="experience_table" class="table table-bordered exp1">
        <thead>
          <tr>
            <td>SL</td>
            <td>Company Name</td>
            <td>Designation</td>
            <td>Duration</td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          @php
            $hasOld = old('company_name');
            $rows = $hasOld ? old('company_name') : [''];
          @endphp
  
          @foreach ($rows as $key => $value)
            <tr class="experience-row">
              <td class="serial">{{ $key + 1 }}</td>
              <td>
                <input type="text" name="company_name[]" value="{{ $hasOld[$key] ?? '' }}"
                       class="form-control input-sm">
              </td>
              <td>
                <input type="text" name="designations[]" value="{{ old('designations')[$key] ?? '' }}"
                       class="form-control input-sm">
              </td>
              <td>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="input-group">
                      <input type="date" name="from_dates[]" value="{{ old('from_dates')[$key] ?? '' }}"
                             class="form-control" placeholder="From">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="input-group">
                      <input type="date" name="to_dates[]" value="{{ old('to_dates')[$key] ?? '' }}"
                             class="form-control" placeholder="To">
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <button type="button"
                        class="btn btn-danger btn-xs remove-experience"
                        @if($key === 0 && !$hasOld) disabled @endif>
                  <i class="fa fa-times"></i>
                </button>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-info btn-sm" id="add_experience">
                  <i class="fa fa-plus"></i> Add
                </button>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  