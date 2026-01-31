<div class="row">
    <div class="col-sm-12 pl-4">
        <h3 class="header smaller lighter blue">Basic Information</h3>
    </div>

    <div class="col-sm-4">
        <div class="form-group mb-25">
            <label class="col-sm-12 control-label"> Name
                <span class="required-text">*</span>
            </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="name" value="{{ old('name') }}"
                    autocomplete="off" placeholder="Type Name" required>

            </div>
        </div>
    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label">Company
                <span class="required-text">*</span>
            </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="company"
                    value="{{ optional($job->branch)->name }}" autocomplete="off" readonly required>

            </div>
        </div>
    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label">Department
                <span class="required-text">*</span>
            </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="department"
                    value="{{ optional($job->department)->name }}" autocomplete="off" readonly required>

            </div>
        </div>
    </div>
    <div class="col-sm-4">


        <div class="form-group mb-25">
            <label class="col-sm-12 control-label">Designation
                <span class="required-text">*</span>
            </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="designation"
                    value="{{ optional($job->designation)->name }}" autocomplete="off" readonly required>

            </div>
        </div>

    </div>
    <div class="col-sm-4">


        <div class="form-group mb-25">
            <label class="col-sm-12 control-label">
                Father/Husband Name
            </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="father_or_husband_name"
                    value="{{ old('father_or_husband_name') }}" autocomplete="off" placeholder="Father/Husband Name"
                    required>

            </div>
        </div>
    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label"> Mother's Name </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="mother_name" value="{{ old('mother_name') }}"
                    placeholder="Mother's Name">

            </div>
        </div>
    </div>
   
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label"> Mobile<span class="text-danger">*</span> </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="mobile" value="{{ old('mobile') }}"
                    placeholder="Mobile">

            </div>
        </div>
    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label"> Email<span class="text-danger">*</span> </label>

            <div class="col-xs-12 col-sm-8">

                <input type="text" class="form-control input-sm" name="email" value="{{ old('email') }}"
                    placeholder="Email">

            </div>
        </div>
    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label"> National Id</label>

            <div class="col-xs-12 col-sm-8 @error('national_id') has-error @enderror">

                <input type="number" class="form-control input-sm" name="national_id"
                    value="{{ old('national_id') }}" placeholder="National Id">

                @error('national_id')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>

    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25">
            <label class="col-sm-12 control-label"> Permanent Address
            </label>

            <div class="col-xs-12 col-sm-8 @error('permanent_address') has-error @enderror">

                <textarea class="form-control input-sm" name="permanent_address" id="permanent_address" 
                    placeholder="Present Address">{{ old('permanent_address') }}</textarea>

                @error('permanent_address')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>

    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25 mb-3">
            <label for="company_logo" class="col-form-label">Image</label>
            
            <input type="file" class="file-control form-control" id="image" name="image"
                value="{{ old('image') }}">
        </div>

    </div>
    <div class="col-sm-4">

        <div class="form-group mb-25 mb-3">
            <label for="company_logo" class="col-form-label">Document</label>
            <input type="file" class="file-control form-control" id="cv" name="cv"
                value="{{ old('cv') }}">
        </div>

    </div>
   
</div>
