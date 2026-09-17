/**
 * Services Management - JavaScript
 */

// ============================================
// DELETE SERVICE (FIXED - Handle Already Deleted)
// ============================================
function deleteService(id) {
    if (confirm('⚠️ Are you sure you want to delete this service?\n\nThis action cannot be undone!')) {
        showLoader();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch('/admin/services/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // ✅ Handle 404 (service already deleted) as success
            if (response.status === 404) {
                return { success: true, already_deleted: true, message: 'Service already deleted.' };
            }
            return response.json();
        })
        .then(data => {
            hideLoader();
            
            if (data.success) {
                // ✅ Different message if already deleted
                if (data.already_deleted) {
                    showNotification('success', data.message || 'Service was already deleted. Refreshing...');
                } else {
                    showNotification('success', data.message || 'Service deleted');
                }
                // ✅ Always reload to sync state
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', data.message || 'Error deleting service');
                // ✅ Still reload after error to sync state
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => {
            hideLoader();
            showNotification('error', 'Network error. Please try again.');
            console.error('Error:', error);
        });
    }
}

// ============================================
// TOGGLE STATUS (FIXED - Handle Not Found)
// ============================================
function toggleStatus(id, currentStatus) {
    let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    let action = newStatus === 'active' ? 'activate' : 'deactivate';

    if (confirm(`Are you sure you want to ${action} this service?`)) {
        showLoader();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(`/admin/services/${id}/status/${newStatus}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // ✅ Handle 404 (service not found)
            if (response.status === 404) {
                return { success: false, already_deleted: true, message: 'Service not found or already deleted.' };
            }
            return response.json();
        })
        .then(data => {
            hideLoader();
            
            if (data.success) {
                showNotification('success', `Service ${action}d successfully!`);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', data.message || 'Error updating status');
                // ✅ Reload to sync state if service not found
                if (data.already_deleted) {
                    setTimeout(() => location.reload(), 1500);
                }
            }
        })
        .catch(error => {
            hideLoader();
            showNotification('error', 'Network error. Please try again.');
            console.error('Error:', error);
        });
    }
}

// ============================================
// SEARCH & FILTER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const filterStatus = document.getElementById('filterStatus');

    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
});

function filterTable() {
    let searchValue = (document.getElementById('search')?.value || '').toLowerCase();
    let statusValue = document.getElementById('filterStatus')?.value || '';
    let rows = document.querySelectorAll('#tableBody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        if (row.id === 'noResultsMsg') return;
        if (row.querySelector('td')) {
            let text = row.textContent.toLowerCase();
            let statusCell = row.querySelector('.status-badge-modern');
            let status = '';

            if (statusCell) {
                let statusText = statusCell.textContent.trim().toLowerCase();
                if (statusText.includes('active') && !statusText.includes('inactive')) status = 'active';
                if (statusText.includes('inactive')) status = 'inactive';
            }

            let matchesSearch = text.includes(searchValue);
            let matchesStatus = !statusValue || status === statusValue;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
    });

    let noResultsMsg = document.getElementById('noResultsMsg');
    if (visibleCount === 0 && rows.length > 0) {
        if (!noResultsMsg) {
            let tbody = document.getElementById('tableBody');
            let msgRow = document.createElement('tr');
            msgRow.id = 'noResultsMsg';
            msgRow.innerHTML = `
                <td colspan="7" style="padding: 40px; text-align: center;">
                    <div class="empty-state" style="padding: 20px;">
                        <i class="fas fa-search" style="font-size: 40px;"></i>
                        <h3>No Matching Services</h3>
                        <p>Try adjusting your search or filter criteria</p>
                    </div>
                </td>
            `;
            tbody.appendChild(msgRow);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

// ============================================
// NOTIFICATION - Mobile friendly
// ============================================
function showNotification(type, message) {
    let notification = document.createElement('div');
    let bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
    let borderColor = type === 'success' ? '#10b981' : '#ef4444';
    let textColor = type === 'success' ? '#065f46' : '#991b1b';
    let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    const isMobile = window.innerWidth <= 576;
    const styles = isMobile
        ? `left: 12px; right: 12px; top: 70px;`
        : `right: 24px; top: 80px; min-width: 280px; max-width: 400px;`;

    notification.style.cssText = `
        position: fixed;
        ${styles}
        padding: 14px 20px;
        background: ${bgColor};
        border-left: 4px solid ${borderColor};
        color: ${textColor};
        border-radius: 12px;
        z-index: 9999;
        animation: svcSlideIn 0.3s ease;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        font-size: 14px;
        font-family: inherit;
        box-sizing: border-box;
    `;
    notification.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'svcSlideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// ============================================
// LOADER
// ============================================
function showLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.add('show');
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.remove('show');
}