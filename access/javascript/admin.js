// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    // Create mobile menu button
    const topBar = document.querySelector('.top-bar');
    const mobileBtn = document.createElement('button');
    mobileBtn.className = 'mobile-menu-btn';
    mobileBtn.innerHTML = '<i class="fas fa-bars"></i>';
    topBar.insertBefore(mobileBtn, topBar.firstChild);
    
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);
    
    // Toggle sidebar
    mobileBtn.addEventListener('click', function() {
        document.querySelector('.sidebar-nav').classList.toggle('active');
        overlay.classList.toggle('active');
    });
    
    // Close sidebar on overlay click
    overlay.addEventListener('click', function() {
        document.querySelector('.sidebar-nav').classList.remove('active');
        this.classList.remove('active');
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
    
    // Confirm actions
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
    
    // Table row click for mobile
    if (window.innerWidth <= 768) {
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('a')) {
                    this.classList.toggle('expanded');
                }
            });
        });
    }
});