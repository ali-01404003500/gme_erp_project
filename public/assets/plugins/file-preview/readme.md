# File Input Preview and Delete

This JavaScript program enhances the functionality of file input fields in HTML documents by providing a visual preview for selected files and a delete button to remove them. It utilizes dynamic element creation and event listeners to achieve this functionality.

## Getting Started

To use this program, include the JavaScript code in your HTML file, making sure to reference the necessary libraries (e.g., Font Awesome for icons). Follow the steps below to integrate the code into your project.

### Prerequisites

- Ensure that Font Awesome is included in your project for icon support.

  ```html
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-pzYOwFtB5n0lU3D8NlHG+O2OMdG1wIS8UjDHkKzjsllMQ2O4V7o4F7GSZ3emOLMaTQdlwYxqL7TpGDL/QykK2Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  ```

### Usage

1. Add the `file-control` class to your file input elements.

   ```html
   <input type="file" class="file-control" />
   ```

2. Include the provided JavaScript code in your HTML file or link to an external script.

   ```html
   <script src="file-preview.js"></script>
   ```

3. Ensure the HTML document is fully loaded before the script runs.

   ```html
   <script>
     document.addEventListener('DOMContentLoaded', function () {
       // ... (Include the provided JavaScript code here)
     });
   </script>
   ```

## Features

- **File Preview:** Dynamically displays previews for selected image files.
- **File Icon:** Displays appropriate icons for non-image files based on their extensions.
- **Delete Button:** Provides a delete button to remove selected files.
- **Styling:** Includes styles for visual appeal, with options for customization.

## Example

```html
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


  <title>File Input Preview and Delete Example</title>

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    integrity="sha512-pzYOwFtB5n0lU3D8NlHG+O2OMdG1wIS8UjDHkKzjsllMQ2O4V7o4F7GSZ3emOLMaTQdlwYxqL7TpGDL/QykK2Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Your custom styles or additional dependencies -->

</head>

<body>

  <!-- File input with the 'file-control' class -->
  <input type="file" class="file-control" />

  <!-- Other HTML content as needed -->

  <!-- Include the JavaScript code -->
  <script src="file-preview-delete.js"></script>
</body>

</html>
```

## Customization

- You can customize the program's appearance by modifying the styles in the JavaScript code.
- Adjust Font Awesome classes in the `getFileIconClass` function for different file types.

Feel free to customize the code to suit your project's specific requirements.