{{-- file --}}
@props(['multiple' => false, 'loadLater'=>false, 'name' => 'file', 'value' => '', 'id' => '', 'class' => ''])

<div class="card @if($class) {{ $class }} @endif" style="border-radius: 15px; border: 1px solid #ccc;"  id="{{ $id }}" @if($class) class="{{ $class }}" @endif>
    <div class="card-body" style="padding: 8px;">
        <!-- Upload area -->
        <div id="drop-area-{{ $name }}"
            style="border: 2px dashed #ccc; border-radius: 10px; padding: 8px; text-align: center; cursor: pointer;">
            <i class="fas fa-upload" style="font-size: 36px; color: #333; margin-bottom: 10px;"></i>
            <h5 class="card-title" style="font-size: 1.25rem; color: #333;">Drop files here or click to upload</h5>

            <!-- Hidden file input - correctly applies multiple prop -->
            <input class="form-control" type="file" id="file-input-{{ $name }}"
                {{ $multiple ? 'multiple' : '' }} style="display: none;">

            <!-- Hidden input for form submission - correctly applies name prop -->
            @if ($multiple)
                <input type="hidden" name="{{ $name }}[]" id="hidden-input-{{ $name }}" value="">
            @else
                <input type="hidden" name="{{ $name }}" id="hidden-input-{{ $name }}" value="">
            @endif
        </div>

        <!-- List of uploaded files -->
        <div id="uploaded-files-{{ $name }}" style="margin-top: 20px;">
            <template id="file-item-template-{{ $name }}">
                <div class="file-item"
                    style="display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; align-items: center; flex: 1;">
                        <img src="" alt="File Icon" class="file-icon"
                            style="width: 30px; height: 30px; margin-right: 10px;">
                        <span class="file-name" style="font-size: 1rem; color: #333;"></span>
                    </div>
                    <div class="file-actions" style="display: flex; align-items: center;">
                        <div class="upload-progress" style="width: 100px; margin-left: 10px;">
                            <div class="progress"
                                style="height: 8px; background-color: #e2e2e2; border-radius: 5px; overflow: hidden;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: 0%; background-color: blue; height: 10px;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="progress-percentage"
                                style="font-size: 0.875rem; color: #666; margin-left: 5px;">0%</span>
                        </div>
                        <span class="upload-status-icon success-icon"
                            style="display: none; color: green; margin-left: 5px;"><i
                                class="fas fa-check-circle"></i></span>
                        <span class="upload-status-icon error-icon"
                            style="display: none; color: red; margin-left: 5px;"><i
                                class="fas fa-times-circle"></i></span>
                        <div class="btn-group dm-button-group">
                            <button type="button" class="btn btn-xs preview-btn"
                                style="margin-left: 2px; display: none;">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-xs open-btn" style="margin-left: 2px; display: none;">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn- remove-file delete-file-btn"
                                aria-label="Delete" style="margin-left: 2px; display: none;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <input type="hidden" class="file-url" value="">
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal for image preview -->
    <div class="modal fade" id="imagePreviewModal-{{ $name }}" tabindex="-1"
    aria-labelledby="imagePreviewModalLabel-{{ $name }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel-{{ $name }}">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage-{{ $name }}" src="" alt="Preview" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>


{{-- @dd($value) --}}

<script>

@if(!$loadLater)
    document.addEventListener('DOMContentLoaded', () => {
        const id = '{{ $id }}';
        const name = '{{ $name }}';
        const multiple = {{ $multiple ? 'true' : 'false' }};
        const initialValue = @if ($multiple) {!! json_encode($value) !!} @else "{{ $value }}" @endif;
        const csrf = "{{ csrf_token() }}";
        const uploader = new FileUploader(id, name, multiple, initialValue, csrf);
    });

@endif

function initializeFileUploader_{{$id}}_{{ $name }}(customClass, loadDraftAndValue = false, values = null) {
    const id = '{{ $id }}';
    const name = '{{ $name }}';
    const multiple = {{ $multiple ? 'true' : 'false' }};
    const initialValue = values !== null ? values : @if ($multiple) {!! json_encode($value) !!} @else "{{ $value }}" @endif;
    const csrf = "{{ csrf_token() }}";
    // Initialize the file uploader with the custom class
    const uploader = new FileUploader(id, name, multiple, initialValue, csrf, customClass, loadDraftAndValue);

    document.getElementById(id).uploader = uploader;
}

</script>

{{-- 
<script>
    function initializeFileUploader_{{$id}}_{{ $name }}(customClass) {
        // Component configuration
        const componentName = "{{ $name }}";
        const isMultiple = {{ $multiple ? 'true' : 'false' }};
        const initialValue =
            @if ($multiple)
                {!! json_encode($value) !!}
            @else
                "{{ $value }}"
            @endif ;

            let fileInput = document.getElementById(`file-input-${componentName}`);
            let uploadedFilesContainer = document.getElementById(`uploaded-files-${componentName}`);
            let fileItemTemplate = document.getElementById(`file-item-template-${componentName}`).content;
            let dropArea = document.getElementById(`drop-area-${componentName}`);
            let hiddenInput = document.getElementById(`hidden-input-${componentName}`);
            let fileUploadDoc = document;
        // DOM Elements
        if (customClass) {
            console.log("init custom class uploaders");
            
            fileInput = document.querySelector(`.${customClass} #file-input-${componentName}`);
            uploadedFilesContainer = document.querySelector(`.${customClass} #uploaded-files-${componentName}`);
            fileItemTemplate = document.querySelector(`.${customClass} #file-item-template-${componentName}`).content;
            dropArea = document.querySelector(`.${customClass} #drop-area-${componentName}`);
            hiddenInput = document.querySelector(`.${customClass} #hidden-input-${componentName}`);
            let fileUploadDoc = document.querySelector(`.${customClass}`);
        }
        


        // CSRF token
        let csrfToken = "{{ csrf_token() }}";
        let fileCounter = 0;

        // STEP 4: Initialize with existing files if value contains URLs
        initializeExistingFiles();

        function initializeExistingFiles() {
            loadDraftUrls();

            if (initialValue) {
                if (Array.isArray(initialValue) && isMultiple) {
                    initialValue.forEach(fileData => {
                        const fileUrl = extractFilePath(fileData);
                        if (fileUrl) addExistingFile(fileUrl);
                    });
                } else if (typeof initialValue === 'string' && initialValue.trim() !== '') {
                    addExistingFile(initialValue);
                } else if (initialValue !== null && typeof initialValue === 'object') {
                    // Handle single file object case
                    const fileUrl = extractFilePath(initialValue);
                    if (fileUrl) addExistingFile(fileUrl);
                }
            }
        }


        function extractFilePath(fileData) {
            if (typeof fileData === 'string') return fileData;
            if (fileData && typeof fileData === 'object') {
                return fileData.path || fileData.url || fileData.file || '';
            }
            return '';
        }

        function loadDraftUrls() {
            const currentUrl = window.location.href;
            const localStorageKey = `file-uploader-draft-urls-${componentName}-${currentUrl}`;
            const draftUrls = JSON.parse(localStorage.getItem(localStorageKey) || '[]');
            console.log('Draft URLs from localStorage:', draftUrls);
            if (draftUrls) {
                // Filter out null values before processing
                const validDraftUrls = draftUrls.filter(url => url !== null);
                validDraftUrls.forEach(fileUrl => {
                    if (fileUrl) addExistingFile(fileUrl);
                });
            }
        }


        function addExistingFile(fileData) {
            const fileUrl = extractFilePath(fileData);
            if (!fileUrl) return;

            const fileId = `file-item-${componentName}-${fileCounter++}`;
            const fileItem = fileItemTemplate.cloneNode(true);
            const fileItemDiv = fileItem.querySelector('.file-item');

            // Determine file name from URL
            const fileName = fileUrl.split('/').pop();
            const fileExtension = fileName.split('.').pop().toLowerCase();
            let fileType = 'application/octet-stream';

            // Set file type based on extension for preview
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                fileType = `image/${fileExtension === 'jpg' ? 'jpeg' : fileExtension}`;
            } else if (fileExtension === 'pdf') {
                fileType = 'application/pdf';
            }

            fileItemDiv.id = fileId;
            fileItemDiv.dataset.fileName = fileName;
            fileItemDiv.dataset.fileType = fileType;

            // Set icon and name
            const fileIcon = fileItem.querySelector('.file-icon');
            fileIcon.src = getFileIcon(fileType);
            fileItem.querySelector('.file-name').textContent = fileName;

            // Hide progress elements
            const progressContainer = fileItem.querySelector('.upload-progress');
            progressContainer.style.display = 'none';

            // Show success indicators and buttons
            const successIcon = fileItem.querySelector('.success-icon');
            const deleteButton = fileItem.querySelector('.delete-file-btn');
            const previewBtn = fileItem.querySelector('.preview-btn');
            const openBtn = fileItem.querySelector('.open-btn');
            const fileUrlInput = fileItem.querySelector('.file-url');

            successIcon.style.display = 'inline-block';
            deleteButton.style.display = 'inline-block';
            fileUrlInput.value = fileUrl;

            // Show preview button for images
            if (fileType.startsWith('image/')) {
                previewBtn.style.display = 'inline-block';
            } else {
                openBtn.style.display = 'inline-block';
            }

            uploadedFilesContainer.appendChild(fileItem);
            updateHiddenInput();
        }

        // Click handler for drop area
        dropArea.addEventListener('click', function(event) {
            if (event.target === dropArea || event.target.closest(`#drop-area-${componentName}`) === dropArea) {
                fileInput.click();
            }
        });

        // STEP 1 & 2: Handle file input change with multiple awareness
        fileInput.addEventListener('change', function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (fileInput.files && fileInput.files.length > 0) {
                const files = Array.from(fileInput.files);

                // STEP 2: If not multiple, clear existing files first
                if (!isMultiple) {
                    //delete existing files

                    clearAllFiles();
                }

                files.forEach(file => {
                    const fileId = addFileItem(file);
                    uploadFile(file, fileId);
                });

                fileInput.value = ''; // Reset file input
            }
        });

        function clearAllFiles() {
            // Select all file items
            const fileItems = uploadedFilesContainer.querySelectorAll('.file-item');

            fileItems.forEach(item => {
                // Find the delete button within each file item and simulate a click
                const deleteButton = item.querySelector('.delete-file-btn');
                if (deleteButton) {
                    deleteButton.click();
                } else {
                    // If no delete button (e.g., existing file not uploaded), just remove the item from UI
                    item.remove();
                }
            });

            // Clear hidden input after all deletions/removals
            if (hiddenInput) {
                hiddenInput.value = '';
            }
            // Clear draft URLs from localStorage
            clearDraftUrls();
        }

        function clearDraftUrls() {
            const currentUrl = window.location.href;
            const localStorageKey = `file-uploader-draft-urls-${componentName}-${currentUrl}`;
            localStorage.removeItem(localStorageKey);
        }

        function getFileIcon(fileType) {
            if (fileType.startsWith('image/')) {
                return 'https://img.icons8.com/color/30/000000/image-file.png';
            } else if (fileType === 'application/pdf') {
                return 'https://img.icons8.com/color/30/000000/pdf-2--v1.png';
            } else if (fileType.startsWith('video/')) {
                return 'https://img.icons8.com/color/30/000000/video-file.png';
            } else if (fileType.startsWith('audio/')) {
                return 'https://img.icons8.com/color/30/000000/audio-file.png';
            } else {
                return 'https://img.icons8.com/color/30/000000/document.png';
            }
        }

        function addFileItem(file) {
            const fileId = `file-item-${componentName}-${fileCounter++}`;
            const fileItem = fileItemTemplate.cloneNode(true);
            const fileItemDiv = fileItem.querySelector('.file-item');

            fileItemDiv.id = fileId;
            fileItemDiv.dataset.fileName = file.name;
            fileItemDiv.dataset.fileType = file.type;

            const fileIcon = fileItem.querySelector('.file-icon');
            fileIcon.src = getFileIcon(file.type);

            fileItem.querySelector('.file-name').textContent = file.name;
            uploadedFilesContainer.appendChild(fileItem);
            return fileId;
        }

        function updateFileProgress(fileId, percent) {
            const fileItem = fileUploadDoc.getElementById(fileId);
            if (fileItem) {
                const progressBar = fileItem.querySelector('.progress-bar');
                const progressPercentage = fileItem.querySelector('.progress-percentage');
                progressBar.style.width = percent + '%';
                progressPercentage.textContent = percent + '%';
            }
        }

        function handleUploadComplete(fileId, response) {
            const fileItem = fileUploadDoc.getElementById(fileId);
            if (!fileItem) return;

            const progressContainer = fileItem.querySelector('.upload-progress');
            const successIcon = fileItem.querySelector('.success-icon');
            const deleteButton = fileItem.querySelector('.delete-file-btn');
            const fileUrl = fileItem.querySelector('.file-url');
            const previewBtn = fileItem.querySelector('.preview-btn');
            const openBtn = fileItem.querySelector('.open-btn');
            const fileType = fileItem.dataset.fileType;

            progressContainer.style.display = 'none';
            successIcon.style.display = 'inline-block';
            deleteButton.style.display = 'inline-block';

            // Store file URL from response
            fileUrl.value = response.path || '';

            // Show preview button for images
            if (fileType && fileType.startsWith('image/')) {
                previewBtn.style.display = 'inline-block';
            } else {
                openBtn.style.display = 'inline-block';
            }

            // STEP 3: Update hidden input with file URL
            updateHiddenInput();

            // Store file URL to localStorage
            storeDraftUrl(fileUrl.value);
        }

        function storeDraftUrl(url) {
            const currentUrl = window.location.href;
            const localStorageKey = `file-uploader-draft-urls-${componentName}-${currentUrl}`;
            let draftUrls = JSON.parse(localStorage.getItem(localStorageKey) || '[]');
            if (!isMultiple) {
                draftUrls = url ? [url] : []; // Single file, replace or clear
            } else {
                draftUrls = draftUrls.filter(draftUrl => draftUrl !== null); // remove null values
                if (url) {
                    draftUrls.push(url);
                }
            }
            localStorage.setItem(localStorageKey, JSON.stringify(draftUrls));
        }

        // STEP 3: Update hidden input for form submission and localStorage
        function updateHiddenInput() {
            // Gather all file URLs
            const fileItems = uploadedFilesContainer.querySelectorAll('.file-item');
            const fileUrls = Array.from(fileItems).map(item =>
                item.querySelector('.file-url').value
            ).filter(url => url);

            if (isMultiple) {
                // For multiple files
                if (hiddenInput.name.endsWith('[]')) {
                    // Handle array notation
                    const parentForm = findParentForm(hiddenInput);

                    // Remove any existing hidden inputs except the first one
                    if (parentForm) {
                        const existingInputs = parentForm.querySelectorAll(`input[name="${hiddenInput.name}"]`);
                        existingInputs.forEach((input, index) => {
                            if (index > 0) input.remove();
                        });

                        // Set first value or clear if empty
                        hiddenInput.value = fileUrls.length > 0 ? fileUrls[0] : '';

                        // Create additional inputs for remaining files
                        for (let i = 1; i < fileUrls.length; i++) {
                            const newInput = document.createElement('input');
                            newInput.type = 'hidden';
                            newInput.name = hiddenInput.name;
                            newInput.value = fileUrls[i];
                            parentForm.appendChild(newInput);
                        }
                    }
                } else {
                    // Store as JSON if not using array notation
                    hiddenInput.value = JSON.stringify(fileUrls);
                }
            } else {
                // For single file, just use the first URL
                hiddenInput.value = fileUrls.length > 0 ? fileUrls[0] : '';
            }

            // Update draft URLs in localStorage
            storeDraftUrl(fileUrls);
        }

        function handleUploadError(fileId) {
            const fileItem = fileUploadDoc.getElementById(fileId);
            if (!fileItem) return;

            const progressContainer = fileItem.querySelector('.upload-progress');
            const errorIcon = fileItem.querySelector('.error-icon');

            progressContainer.style.display = 'none';
            errorIcon.style.display = 'inline-block';
        }

        function findParentForm(element) {
            let parent = element.parentElement;
            while (parent) {
                if (parent.tagName === 'FORM') {
                    return parent;
                }
                parent = parent.parentElement;
            }
            return null;
        }

        function uploadFile(file, fileId) {
            const formData = new FormData();
            const url = "{{ route('upload_file') }}";
            formData.append('file', file);

            if (csrfToken) {
                formData.append('_token', csrfToken);
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    let percent = (e.loaded / e.total) * 100;
                    updateFileProgress(fileId, Math.round(percent));
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.response);
                        handleUploadComplete(fileId, response);
                    } catch (error) {
                        console.error('Error parsing response:', error);
                        handleUploadError(fileId);
                    }
                } else {
                    console.error('Upload error', xhr.status, xhr.statusText);
                    handleUploadError(fileId);
                }
            };

            xhr.onerror = function() {
                console.error("Network error during upload.");
                handleUploadError(fileId);
            };

            xhr.send(formData);
        }

        // Event delegation for file actions
        uploadedFilesContainer.addEventListener('click', function(event) {
            // Delete button handler
            if (event.target.classList.contains('delete-file-btn') || event.target.closest(
                    '.delete-file-btn')) {
                const fileItem = event.target.closest('.file-item');
                const fileUrl = fileItem.querySelector('.file-url').value;

                if (fileUrl) {
                    fetch(fileUrl, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        })
                        .then(response => {
                            if (!response.ok)
                                return Promise.reject(response);
                            return response.json();
                        })
                        .then(() => {
                            fileItem.remove();
                            updateHiddenInput();
                        })
                        .catch(error => {
                            console.error('Error deleting file:', error);
                        }).finally(() => {
                            fileItem.remove();
                            updateHiddenInput();
                        });
                } else {
                    // If no URL, just remove from UI
                    fileItem.remove();
                    updateHiddenInput();
                }
            }

            // Preview button handler
            else if (event.target.classList.contains('preview-btn') || event.target.closest(
                    '.preview-btn')) {
                const fileItem = event.target.closest('.file-item');
                const fileUrl = fileItem.querySelector('.file-url').value;
                const fileName = fileItem.dataset.fileName;

                const previewImage = fileUploadDoc.getElementById(`previewImage-${componentName}`);
                const modalTitle = fileUploadDoc.getElementById(`imagePreviewModalLabel-${componentName}`);

                previewImage.src = fileUrl;
                modalTitle.textContent = fileName;

                // Show modal
                if (typeof bootstrap !== 'undefined') {
                    const imageModal = new bootstrap.Modal(fileUploadDoc.getElementById(
                        `imagePreviewModal-${componentName}`));
                    imageModal.show();
                } else {
                    fileUploadDoc.getElementById(`imagePreviewModal-${componentName}`).style.display =
                        'block';
                }
            }

            // Open button handler
            else if (event.target.classList.contains('open-btn') || event.target.closest('.open-btn')) {
                const fileItem = event.target.closest('.file-item');
                const fileUrl = fileItem.querySelector('.file-url').value;
                window.open(fileUrl, '_blank');
            }
        });

        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // STEP 5: Clear localStorage on form submit
        const parentForm = findParentForm(dropArea);
        if (parentForm) {
            parentForm.addEventListener('submit', function() {
                clearDraftUrls();
            });
        }

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('dragover');
        }

        function unhighlight() {
            dropArea.classList.remove('dragover');
        }

        // Handle file drop
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files && files.length > 0) {
                const filesArray = Array.from(files);

                // STEP 2: If not multiple, clear existing files first
                if (!isMultiple) {
                    clearAllFiles();
                }

                filesArray.forEach(file => {
                    const fileId = addFileItem(file);
                    uploadFile(file, fileId);
                });
            }
        }
    }
    document.addEventListener('DOMContentLoaded', ()=>initializeFileUploader_{{$id}}_{{ $name }}());
</script> --}}

<style>
    .dragover {
        border: 2px dashed #007bff !important;
        background-color: rgba(0, 123, 255, 0.05);
    }

    .file-item {
        transition: background-color 0.2s;
    }

    .file-item:hover {
        background-color: #f8f9fa;
    }

    #drop-area-{{ $name }} {
        transition: all 0.3s ease;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
</style>
