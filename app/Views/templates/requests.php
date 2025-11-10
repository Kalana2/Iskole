<!-- filepath: /home/snake/Projects/Iskole/app/Views/templates/requests.php -->
<link rel="stylesheet" href="/css/request/request.css">

<div class="box">
    <div class="container info-box-large">
        <div class="heading-section">
            <span class="heading-text">Pending Leave Requests</span>
            <span class="sub-heding-text">Review and approve leave requests</span>
        </div>
        
        <div class="info-box border-container">
            <div class="left">
                <span class="heading-name">K K Jina</span>
                <span class="sub-heading">Teacher - Nov 25 2025</span>
                <span class="sub-heading-bolt">Medical Leave</span>
                <span class="sub-heading">Submitted on Nov 15 2025</span>
                <label class="label label-green">18 days remaining</label>
            </div>
            <div class="right two-com">
                <button class="btn btn-green">Approve</button>
                <button class="btn btn-red">Reject</button>
            </div>
        </div>
        
        <div class="info-box border-container">
            <div class="left">
                <span class="heading-name">Sarah Johnson</span>
                <span class="sub-heading">Teacher - Nov 28 2025</span>
                <span class="sub-heading-bolt">Annual Leave</span>
                <span class="sub-heading">Submitted on Nov 18 2025</span>
                <label class="label label-green">16 days remaining</label>
            </div>
            <div class="right two-com">
                <button class="btn btn-green">Approve</button>
                <button class="btn btn-red">Reject</button>
            </div>
        </div>
        
        <div class="info-box border-container">
            <div class="left">
                <span class="heading-name">Michael Chen</span>
                <span class="sub-heading">Teacher - Dec 05 2025</span>
                <span class="sub-heading-bolt">Personal Leave</span>
                <span class="sub-heading">Submitted on Nov 20 2025</span>
                <label class="label label-red">5 days remaining</label>
            </div>
            <div class="right two-com">
                <button class="btn btn-green">Approve</button>
                <button class="btn btn-red">Reject</button>
            </div>
        </div>
        
        <div class="info-box border-container">
            <div class="left">
                <span class="heading-name">Emily Rodriguez</span>
                <span class="sub-heading">Teacher - Dec 10 2025</span>
                <span class="sub-heading-bolt">Sick Leave</span>
                <span class="sub-heading">Submitted on Nov 22 2025</span>
                <label class="label label-green">12 days remaining</label>
            </div>
            <div class="right two-com">
                <button class="btn btn-green">Approve</button>
                <button class="btn btn-red">Reject</button>
            </div>
        </div>
    </div>
</div>

<script>
// Add click handlers for approve/reject buttons
document.addEventListener('DOMContentLoaded', function() {
    const approveButtons = document.querySelectorAll('.btn-green');
    const rejectButtons = document.querySelectorAll('.btn-red');
    
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const requestCard = this.closest('.info-box');
            const employeeName = requestCard.querySelector('.heading-name').textContent;
            
            if (confirm(`Approve leave request for ${employeeName}?`)) {
                // Add your approval logic here
                requestCard.style.opacity = '0.5';
                requestCard.style.pointerEvents = 'none';
                
                // Show success message
                showNotification('Leave request approved successfully!', 'success');
            }
        });
    });
    
    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const requestCard = this.closest('.info-box');
            const employeeName = requestCard.querySelector('.heading-name').textContent;
            
            if (confirm(`Reject leave request for ${employeeName}?`)) {
                // Add your rejection logic here
                requestCard.style.opacity = '0.5';
                requestCard.style.pointerEvents = 'none';
                
                // Show warning message
                showNotification('Leave request rejected.', 'warning');
            }
        });
    });
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #f59e0b, #d97706)'};
            color: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
});

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
