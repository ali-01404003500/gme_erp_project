const fileInputs = document.querySelectorAll('.file-control');

document.addEventListener('DOMContentLoaded', function () {
  console.log("File found in DOM : ", fileInputs.length);
  fileInputs.forEach(function (fileInput) {
    // Create file container for preview
    const fileContainer = document.createElement('div');
    fileContainer.id = 'fileContainer';
    fileContainer.style.padding = '4px';
    fileInput.parentNode.appendChild(fileContainer);

    // Store active files for this input
    const activeFiles = new Map();
    
    // Check if there's a data-value attribute to initially show
    if (fileInput.hasAttribute('data-value')) {
      const dataValue = fileInput.getAttribute('data-value');
      if (dataValue) {
        try {
          // Try to parse as JSON if it's a JSON string of URLs
          const urls = JSON.parse(dataValue);
          if (Array.isArray(urls)) {
            urls.forEach(url => createPreviewFromURL(url, fileContainer, fileInput));
          } else {
            createPreviewFromURL(dataValue, fileContainer, fileInput);
          }
        } catch (e) {
          // If not JSON, treat as a single URL
          createPreviewFromURL(dataValue, fileContainer, fileInput);
        }
      }
    }

    // Add event listener to the file input
    fileInput.addEventListener('change', function (event) {
      const files = event.target.files;
      
      if (files) {
        // For single file input, clear existing files
        if (!fileInput.multiple) {
          activeFiles.clear();
          fileContainer.innerHTML = '';
        }
        
        // Process new files
        for (let i = 0; i < files.length; i++) {
          const file = files[i];
          // Use a unique key combining name and last modified time
          const fileKey = `${file.name}-${file.lastModified}`;
          activeFiles.set(fileKey, file);
        }
        
        // Re-render all previews
        updateFilePreviews(fileInput, fileContainer, activeFiles);
      }
    });
    
    // Store the activeFiles map with the input for reference
    fileInput.activeFiles = activeFiles;
  });
});

function updateFilePreviews(fileInput, fileContainer, activeFiles) {
  // Clear all existing previews
  fileContainer.innerHTML = '';
  
  // Create a new DataTransfer to hold all active files
  const newFilesList = new DataTransfer();
  
  // Display previews for all active files
  activeFiles.forEach((file, fileKey) => {
    // Add to the file list
    newFilesList.items.add(file);
    
    // Create preview element
    const filePreview = createFilePreviewElement(file, fileInput, fileContainer, activeFiles);
    fileContainer.appendChild(filePreview);
  });
  
  // Update the file input with all active files
  fileInput.files = newFilesList.files;
}

function createFilePreviewElement(file, fileInput, fileContainer, activeFiles) {
  const imgWidth = 96;
  const imgHeight = 96;
  
  const filePreview = document.createElement('div');
  filePreview.className = 'file-preview-item';
  filePreview.style.display = 'flex';
  filePreview.style.alignItems = 'center';
  filePreview.style.border = '1px solid #ccc';
  filePreview.style.padding = '8px';
  filePreview.style.margin = '4px';
  filePreview.style.borderRadius = '4px';
  
  const previewContainer = document.createElement('div');
  const fileDetails = document.createElement('div');
  fileDetails.style.padding = '8px';
  fileDetails.style.flexGrow = '1';
  
  // Create remove button for individual file
  const removeButton = document.createElement('button');
  removeButton.type = 'button';
  removeButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
  removeButton.style.border = 'none';
  removeButton.style.backgroundColor = 'transparent';
  removeButton.style.color = 'red';
  removeButton.style.cursor = 'pointer';
  removeButton.style.padding = '4px';
  
  // Generate a unique key for this file
  const fileKey = `${file.name}-${file.lastModified}`;
  
  removeButton.addEventListener('click', function() {
    // Remove this file from the active files map
    activeFiles.delete(fileKey);
    
    // Re-render all previews
    updateFilePreviews(fileInput, fileContainer, activeFiles);
  });
  
  filePreview.appendChild(previewContainer);
  filePreview.appendChild(fileDetails);
  filePreview.appendChild(removeButton);
  
  // Create preview
  const reader = new FileReader();
  reader.onload = function(e) {
    const fileType = file.type.split('/')[0];
    if (fileType === 'image') {
      const img = new Image();
      img.src = e.target.result;
      img.width = imgWidth;
      img.height = imgHeight;
      img.style.objectFit = 'cover';
      previewContainer.appendChild(img);
    } else {
      const iconClass = getFileIconClass(file.name);
      const icon = document.createElement('i');
      icon.classList.add('fas', iconClass);
      icon.style.fontSize = '48px';
      previewContainer.appendChild(icon);
    }
  };
  reader.readAsDataURL(file);
  
  // Add file details
  const nameDiv = document.createElement('div');
  nameDiv.innerHTML = `Name: ${file.name}`;
  const sizeDiv = document.createElement('div');
  sizeDiv.innerHTML = `Size: ${(file.size / (1024 * 1024)).toFixed()} MB`;
  
  fileDetails.appendChild(nameDiv);
  fileDetails.appendChild(sizeDiv);
  
  return filePreview;
}

function createPreviewFromURL(url, fileContainer, fileInput) {
  // If not multiple, clear the container
  if (!fileInput.multiple) {
    fileContainer.innerHTML = '';
  }
  
  const filePreview = document.createElement('div');
  filePreview.className = 'file-preview-item data-value-item';
  filePreview.setAttribute('data-url', url);
  filePreview.style.display = 'flex';
  filePreview.style.alignItems = 'center';
  filePreview.style.border = '1px solid #ccc';
  filePreview.style.padding = '8px';
  filePreview.style.margin = '4px';
  filePreview.style.borderRadius = '4px';
  
  const previewContainer = document.createElement('div');
  const fileDetails = document.createElement('div');
  fileDetails.style.padding = '8px';
  fileDetails.style.flexGrow = '1';
  
  // Create remove button for this preview
  const removeButton = document.createElement('button');
  removeButton.type = 'button';
  removeButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
  removeButton.style.border = 'none';
  removeButton.style.backgroundColor = 'transparent';
  removeButton.style.color = 'red';
  removeButton.style.cursor = 'pointer';
  removeButton.style.padding = '4px';
  
  removeButton.addEventListener('click', function() {
    filePreview.remove();
    
    // Update data-value attribute if needed
    // Here you would implement logic to update the data-value attribute
    // based on the remaining previews with data-url attributes
  });
  
  filePreview.appendChild(previewContainer);
  filePreview.appendChild(fileDetails);
  filePreview.appendChild(removeButton);
  fileContainer.appendChild(filePreview);
  
  // Determine if it's an image by URL extension
  const fileExtension = url.split('.').pop().toLowerCase();
  const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(fileExtension);
  
  if (isImage) {
    const img = new Image();
    img.src = url;
    img.width = 96;
    img.height = 96;
    img.style.objectFit = 'cover';
    previewContainer.appendChild(img);
  } else {
    const iconClass = getFileIconClass(url);
    const icon = document.createElement('i');
    icon.classList.add('fas', iconClass);
    icon.style.fontSize = '48px';
    previewContainer.appendChild(icon);
  }
  
  // Extract filename from URL
  const fileName = url.split('/').pop();
  const nameDiv = document.createElement('div');
  nameDiv.innerHTML = `Name: ${fileName}`;
  fileDetails.appendChild(nameDiv);
}

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