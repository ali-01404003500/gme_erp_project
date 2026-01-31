<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">


    <title>Permission Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar { height: 100vh; overflow-y: auto; }
        .main-content { height: 100vh; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Permission Requests</span>
                    </h6>
                    <ul class="nav flex-column" id="requestsList">
                        @foreach($requests as $request)
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-request-id="{{ $request->id }}">
                                    Request #{{ $request->id }} ({{ $request->status }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Permission Request Details</h1>
                </div>
                <div id="requestDetails">
                    <p>Select a request from the sidebar to view details.</p>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const $requestsList = $('#requestsList');
            const $requestDetails = $('#requestDetails');

            $requestsList.on('click', 'a', function(e) {
                e.preventDefault();
                const requestId = $(this).data('request-id');
                fetchRequestDetails(requestId);
            });

            function fetchRequestDetails(id) {
                $.get(`/requests/${id}`)
                    .done(function(data) {
                        displayRequestDetails(data);
                    })
                    .fail(function(error) {
                        console.error('Error:', error);
                    });
            }

            function displayRequestDetails(request) {
                const html = `
                    <h3>Request #${request.id}</h3>
                    <p><strong>Type:</strong> ${request.type}</p>
                    <p><strong>Details:</strong> ${request.details}</p>
                    <p><strong>Status:</strong> ${request.status}</p>
                    <p><strong>Remarks:</strong> <textarea id="remarks" class="form-control">${request.remarks || ''}</textarea></p>
                    <button class="btn btn-success approve-btn" data-id="${request.id}">Approve</button>
                    <button class="btn btn-danger deny-btn" data-id="${request.id}">Deny</button>
                `;
                $requestDetails.html(html);
            }

            $requestDetails.on('click', '.approve-btn, .deny-btn', function() {
                const id = $(this).data('id');
                const status = $(this).hasClass('approve-btn') ? 'approved' : 'denied';
                const remarks = $('#remarks').val();
                updateRequest(id, status, remarks);
            });

            function updateRequest(id, status, remarks) {
                $.ajax({
                    url: `/requests/${id}`,
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({ status, remarks }),
                    contentType: 'application/json',
                    success: function(data) {
                        alert(data.message);
                        location.reload(); // Refresh the page to update the sidebar
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    </script>
</body>
</html>