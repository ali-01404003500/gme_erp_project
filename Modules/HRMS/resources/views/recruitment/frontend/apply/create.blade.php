@extends('HRMS::recruitment.frontend.layout.master')
@section('title', 'Job Apply')
@section('page-header')
    <i class="fa fa-tachometer"></i> Job Apply
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

        .required-text {
            color: maroon;
        }
    </style>
@endsection


@section('content')

    <div class="container-fluid">
        <div class="social-dash-wrap">

            <x-error-alart />

            <form action="{{ route('carrier.apply.store', $job->id) }}" method="POST" class="form-horizontal"
                id="employee_form" enctype="multipart/form-data">
                @csrf

                <div class="card mb-50 m-50 pl-3 p-4">
                    <div class="row justify-content-center"> <!-- Removed the id from the row -->
                        <div class="col-sm-12 text-center">
                            <div class="inner-page-title pt-1">
                                <h4 class="text-capitalize breadcrumb-title">{{ $job->title }}</h4>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <div class="row">

                                    @include('HRMS::recruitment.frontend.apply.includes.create.basic')

                                    @include('HRMS::recruitment.frontend.apply.includes.create.education')

                                    @include('HRMS::recruitment.frontend.apply.includes.create.experience')

                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit"
                                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


@endsection

@section('page_scripts')
 
    <script>
        const initialRow = $("#education_table tbody tr:first-child").clone();

        initialRow.find('input').val('');
        initialRow.find('#remove_row').removeClass('disabled').removeAttr('disabled');


        function updateSerialNumbers() {
            $("#education_table tbody tr").each(function(index) {
                $(this).find('td:first-child').text(index + 1);
            });
        }

        $("#add_row").click(function() {
            const newRow = initialRow.clone();
            $("#education_table tbody").append(newRow);
            updateSerialNumbers();
        });

        function removeRow(button) {
            $(button).closest('tr').remove();
            updateSerialNumbers();
        }
    </script>
    <script>
        $(function() {
          // Cache the template row (clone, then clear inputs & remove any disabled)
          const $template = $('#experience_table tbody tr.experience-row:first').clone();
          $template.find('input').val('');
          $template.find('.remove-experience').prop('disabled', false);
      
          // Function to re-number serials and toggle remove buttons
          function updateSerials() {
            $('#experience_table tbody tr.experience-row').each(function(i) {
              $(this).find('.serial').text(i + 1);
              // disable remove on first row if it's the only one
              const $btn = $(this).find('.remove-experience');
              if ($('#experience_table tbody tr.experience-row').length === 1) {
                $btn.prop('disabled', true);
              } else {
                $btn.prop('disabled', false);
              }
            });
          }
      
          // Add new row
          $('#add_experience').on('click', function() {
            const $new = $template.clone();
            $('#experience_table tbody').append($new);

            $('.flatdate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            
            updateSerials();
          });
      
          // Remove row (event delegation)
          $('#experience_table').on('click', '.remove-experience', function() {
            $(this).closest('tr').remove();
            updateSerials();
          });
        });
      </script>
      

    <script>
        const fileInputs = document.querySelectorAll('.file-control');

        //document content loaded listener
        document.addEventListener('DOMContentLoaded', function() {
            console.log("File found in DOM : ", fileInputs.length);
            fileInputs.forEach(function(fileInput) {
                //append a file container for preview before upload
                const fileContainer = document.createElement('div');
                fileContainer.id = 'fileContainer';
                // padding 4xp
                fileContainer.style.padding = '4px';

                fileInput.parentNode.appendChild(fileContainer, fileInput);
                //add button
                const buttonDiv = document.createElement('button');
                //type
                buttonDiv.type = 'button';
                buttonDiv.innerHTML = '<i class="fas fa-trash-alt"></i>';
                // float right
                buttonDiv.style.float = 'right';
                //style as buttons
                buttonDiv.style.border = 'none';
                buttonDiv.style.backgroundColor = 'transparent';
                buttonDiv.style.color = 'red';
                buttonDiv.style.cursor = 'pointer';
                //padding
                buttonDiv.style.padding = '4px';
                //hide button
                buttonDiv.style.display = 'none';
                fileInput.parentNode.insertBefore(buttonDiv, fileInput);


                // Add event listener to the file input
                fileInput.addEventListener('change', function(event) {


                    //clear file container
                    fileContainer.innerHTML = '';


                    const imgWidth = 96;
                    const imgHeight = 96;
                    const files = event.target.files;
                    if (files) {
                        for (let i = 0; i < files.length; i++) {
                            const filePriview = document.createElement('div');
                            // flex display
                            filePriview.style.display = 'flex';
                            //align items center
                            filePriview.style.alignItems = 'center';
                            // border
                            filePriview.style.border = '1px solid #ccc';
                            // padding
                            filePriview.style.padding = '8px';
                            // margin
                            filePriview.style.margin = '4px';
                            //border
                            filePriview.style.borderRadius = '4px';
                            const previewContainer = document.createElement('div');
                            const fileDetails = document.createElement('div');
                            fileDetails.style.padding = '8px';
                            filePriview.appendChild(previewContainer);
                            filePriview.appendChild(fileDetails);
                            fileContainer.appendChild(filePriview);
                            const file = files[i];
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const fileType = file.type.split('/')[0];
                                if (fileType === 'image') {
                                    const img = new Image();
                                    img.src = e.target.result;
                                    img.id = 'preview';
                                    img.width = imgWidth;
                                    img.height = imgHeight;
                                    previewContainer.appendChild(img);
                                } else {
                                    const iconClass = getFileIconClass(file.name);
                                    const icon = document.createElement('i');
                                    icon.classList.add('fas', iconClass);
                                    icon.style.fontSize = '48px';
                                    previewContainer.appendChild(icon);
                                }
                            }

                            reader.readAsDataURL(file);

                            // Display file details
                            // fileDetails.innerHTML = `
                        // <div>Name: ${file.name}</div>
                        // <div>Size: ${(file.size / (1024 * 1024)).toFixed()} MB</div>
                        // <button style="margin-top: 8px;" id="removeFile">Remove <i class="fas fa-trash-alt"></i></button>

                        //create divs for name and size with button 
                        const nameDiv = document.createElement('div');
                        nameDiv.innerHTML = `Name: ${file.name}`;
                        const sizeDiv = document.createElement('div');
                        sizeDiv.innerHTML =
                        `Size: ${(file.size / (1024 * 1024)).toFixed()} MB`;

                            fileDetails.appendChild(nameDiv);
                            fileDetails.appendChild(sizeDiv);
                            //show button
                            buttonDiv.style.display = 'block';
                        }
                    }
                });


                // Add event listener to the remove button
                buttonDiv.addEventListener('click', function() {
                    //remove file from input
                    fileInput.value = '';
                    //remove file preview
                    fileContainer.innerHTML = '';
                    //hide button
                    buttonDiv.style.display = 'none';
                });

            });

        });

        function getFileIconClass(fileName) {
            const fileExtension = fileName.split('.').pop().toLowerCase();
            switch (fileExtension) {
                case 'pdf':
                    return 'fa-file-pdf';
                case 'doc':
                case 'docx':
                    return 'fa-file-word';
                case 'xls':
                case 'xlsx':
                    return 'fa-file-excel';
                case 'ppt':
                case 'pptx':
                    return 'fa-file-powerpoint';
                default:
                    return 'fa-file';
            }
        }
    </script>


@endsection
