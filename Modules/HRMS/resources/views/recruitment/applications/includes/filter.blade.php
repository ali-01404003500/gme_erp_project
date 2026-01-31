<div class="row">
    <form action="" method="GET">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="20%">Branch</th>
                        <th width="20%">Job Title</th>
                        <th width="20%">Status</th>
                        <th width="25%">Date</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="branch_id" class="form-control tom-select" 
                                data-selected="{{ request('branch_id') }}">
                                <option value="">Select Branch</option>
                                @foreach ($branchs ?? [] as $id => $name)
                                    <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : ''  }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="job_id" class="form-control tom-select" 
                                data-selected="{{ request('job_id') }}">
                                <option value=""> Choose Job</option>
                                @foreach ($jobs ?? [] as $id => $title)
                                    <option value="{{ $id }}" {{ request('job_id')==$id ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="status" class="form-control tom-select"
                                data-selected="{{ request('status') }}">
                                <option value="">Select Status</option>
                                <option value="0" {{ request('status') == 0 ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Select For Interview</option>
                                <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Attended</option>
                                <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Selected</option>
                                <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Hired</option>
                            </select>
                        </td>
                        <td >
                            <div class="input-daterange input-group">
                                <input type="text" class="form-control flatdaterange" name="from_to" value="{{ request('from_to') }}">
                                {{-- <input type="text" class="form-control flatdate" name="from"
                                    value="{{ request('from') }}" autocomplete="off"
                                    placeholder="From" />
                                <span class="input-group-text">
                                    <i class="fa fa-exchange-alt"></i>
                                </span>

                                <input type="text" class="form-control flatdate" name="to"
                                    value="{{ request('to') }}" autocomplete="off" placeholder="To" /> --}}
                            </div>
                        </td>
                        <td  class="text-right">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                    Search</button>
                                <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                        class="fa fa-refresh"></i> Refresh</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
