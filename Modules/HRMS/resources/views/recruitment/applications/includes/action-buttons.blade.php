<div class="btn-group btn-group-sm" role="group" aria-label="Small button group">

        @if ($jobApplication->status == 0)
        <a href="javascript:void(0)" onclick="updateStatus(`{{ $jobApplication->id }}`,1)" class="btn btn btn-outline-success" title="Select For Interview">
            <i class="fa fa-check"></i>
        </a>
        @elseif($jobApplication->status == 1)
            <a href="javascript:void(0)" onclick="updateStatus(`{{ $jobApplication->id }}`,2)" class="btn btn btn-outline-success" title="Attended">
                <i class="fa fa-check"></i>
            </a>
        @elseif($jobApplication->status == 2)
            <a href="javascript:void(0)" onclick="updateStatus(`{{ $jobApplication->id }}`,3)" class="btn btn btn-outline-success" title="Selected">
                <i class="fa fa-check"></i>
            </a>
        @endif

        @if ($jobApplication->status == 3)
            <a href="javascript:void(0)" onclick="AddToEmployee(`{{ $jobApplication->id }}`)" class="btn btn-outline-success" title="Add To Employee">
                <i class="fa fa-group"></i>
            </a>
        @endif

    @if (hasPermission('hrm.job-applications.show'))
        <a href="{{ route('hrm.job-applications.show', $jobApplication->id) }}" target="_blank" role="button"
            data-toggle="modal" class="btn btn-outline-primary" title="view Details">
            <i class="fa fa-eye"></i>
        </a>
    @endif

    @if (hasPermission('hrm.job-applications.destroy'))
    <button type="button" data-action="{{ route('hrm.job-applications.destroy', $jobApplication->id) }}"
        class="btn btn-outline-danger delete-confirm"><i
            class="far fa-trash-alt"></i></button>
    @endif

</div>
