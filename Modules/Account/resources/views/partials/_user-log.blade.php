
<span class="btn btn-info btn-{{ isset($icon_size) ? $icon_size : 'xs' }} popover-success"
      data-bs-toggle="tooltip"
      data-bs-html="true"
      data-bs-placement="top"
      title="<i class='fa fa-info-circle text-success'></i> Log Information<br>
             <p>Created By: {{ optional($data->created_user)->name }}</p>
             <p>Created At: {{ $data->created_at }}</p>
             <p>Updated By: {{ optional($data->updated_user)->name }}</p>
             <p>Updated At: {{ $data->updated_at }}</p>">
    <i class="fa fa-info-circle"></i>
</span>
