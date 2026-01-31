<script>
    // Utility function to create and show a Bootstrap modal
function showModal(title, body, buttons) {
  const modalHtml = `
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">${title}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">${body}</div>
          <div class="modal-footer">${buttons}</div>
        </div>
      </div>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', modalHtml);
  const modal = new bootstrap.Modal(document.getElementById('permissionModal'));
  modal.show();

  return modal;
}

// Function to send a permission request via API
async function sendPermissionRequest(requestDetails) {
  try {
    const response = await fetch('/api/permission-request', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(requestDetails),
    });

    if (!response.ok) {
      throw new Error('Failed to send permission request');
    }

    const data = await response.json();
    return data.requestId;
  } catch (error) {
    console.error('Error sending permission request:', error);
    throw error;
  }
}

// Function to check for pending permission requests
async function checkPermissionRequests() {
  try {
    const response = await fetch('/api/pending-requests');
    if (!response.ok) {
      throw new Error('Failed to fetch pending requests');
    }

    const requests = await response.json();

    if (requests.length > 0) {
      const requestsList = requests.map(req => `<li>${req.id}: ${req.details}</li>`).join('');
      const modalBody = `<ul>${requestsList}</ul>`;
      const modalButtons = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="handleBulkApproval()">Approve All</button>
        <button type="button" class="btn btn-danger" onclick="handleBulkDenial()">Deny All</button>
      `;

      showModal('Pending Requests', modalBody, modalButtons);
    } else {
      showModal('Pending Requests', 'No pending permission requests.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
    }
  } catch (error) {
    console.error('Error checking permission requests:', error);
    showModal('Error', 'Failed to fetch pending requests.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  }
}

// Function to handle bulk approval of requests
async function handleBulkApproval() {
  try {
    const response = await fetch('/api/approve-all', { method: 'POST' });
    if (!response.ok) {
      throw new Error('Failed to approve all requests');
    }
    showModal('Success', 'All pending requests have been approved.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  } catch (error) {
    console.error('Error approving requests:', error);
    showModal('Error', 'Failed to approve requests.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  }
}

// Function to handle bulk denial of requests
async function handleBulkDenial() {
  try {
    const response = await fetch('/api/deny-all', { method: 'POST' });
    if (!response.ok) {
      throw new Error('Failed to deny all requests');
    }
    showModal('Success', 'All pending requests have been denied.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  } catch (error) {
    console.error('Error denying requests:', error);
    showModal('Error', 'Failed to deny requests.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  }
}

// Function to simulate sending a permission request
async function simulateRequest() {
  try {
    const requestDetails = { type: 'camera_access', details: 'Access to camera requested' };
    const requestId = await sendPermissionRequest(requestDetails);
    showModal('Request Sent', `Permission request sent with ID: ${requestId}`, '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  } catch (error) {
    showModal('Error', 'Failed to send permission request.', '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>');
  }
}

// Set up event listeners
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('sendRequestBtn').addEventListener('click', simulateRequest);
  document.getElementById('checkRequestsBtn').addEventListener('click', checkPermissionRequests);
});

// Periodically check for permission requests (every 30 seconds)
setInterval(checkPermissionRequests, 30000);
</script>