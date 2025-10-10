// ========================================
// DASHBOARD JAVASCRIPT
// Handles mobile menu, logout, navigation, and interactions
// ========================================

// Wait for DOM to fully load before executing
document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // NAVIGATION SYSTEM
    // ========================================
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the target section from data attribute
            const targetSection = this.getAttribute('data-section');
            
            // Remove active class from all nav links
            navLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked nav link
            this.classList.add('active');
            
            // Hide all sections
            sections.forEach(section => section.classList.remove('active'));
            
            // Show target section
            const targetElement = document.getElementById(targetSection + '-section');
            if (targetElement) {
                targetElement.classList.add('active');
            }
            
            // Close mobile menu if open
            const navLinksContainer = document.getElementById('navLinks');
            navLinksContainer.classList.remove('active');
            
            // Scroll to top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    
    // ========================================
    // MOBILE MENU TOGGLE
    // ========================================
    const menuToggle = document.getElementById('menuToggle');
    const navLinksContainer = document.getElementById('navLinks');

    // Toggle mobile menu when hamburger icon is clicked
    menuToggle.addEventListener('click', function() {
        navLinksContainer.classList.toggle('active');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = menuToggle.contains(event.target) || navLinksContainer.contains(event.target);
        
        // If click was outside menu and menu is open, close it
        if (!isClickInside && navLinksContainer.classList.contains('active')) {
            navLinksContainer.classList.remove('active');
        }
    });

    // ========================================
    // LOGOUT BUTTON
    // ========================================
    const logoutBtn = document.querySelector('.logout-btn');
    
    logoutBtn.addEventListener('click', function() {
        // Show confirmation dialog
        if (confirm('Are you sure you want to logout?')) {
            alert('Logging out...');
            
            // In a real application, redirect to login page
            // window.location.href = 'Attendance.html';
        }
    });

    // ========================================
    // VIEW REPORT BUTTONS
    // ========================================
    const viewButtons = document.querySelectorAll('.view-btn');
    
    // Add click handler to each view button
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Find the parent report item
            const reportItem = this.closest('.report-item');
            
            // Get the report name from the h3 element
            const reportName = reportItem.querySelector('h3').textContent;
            
            // Show alert with report name
            alert('Opening report: ' + reportName);
            
            // In a real application, you would:
            // 1. Fetch report data from server
            // 2. Navigate to report details page
            // 3. Or open a modal with report content
            // window.location.href = 'report-details.html?id=' + reportId;
        });
    });

    // ========================================
    // QUICK ACTION BUTTONS
    // ========================================
    const actionButtons = document.querySelectorAll('.action-btn');
    
    // Add click handler to each quick action button
    actionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Get button text and remove extra whitespace
            const actionText = this.textContent.trim();
            
            // Show alert with action
            alert('Action: ' + actionText);
            
            // In a real application, you would:
            // - Navigate to appropriate page
            // - Open a modal/form
            // - Execute the requested action
            
            // Example implementations:
            // if (actionText.includes('Add New Course')) {
            //     window.location.href = 'add-course.html';
            // }
            // else if (actionText.includes('Schedule Session')) {
            //     openScheduleModal();
            // }
            // else if (actionText.includes('Mark Attendance')) {
            //     window.location.href = 'mark-attendance.html';
            // }
        });
    });

    // ========================================
    // COURSE ITEM CLICK (Optional Enhancement)
    // ========================================
    const courseItems = document.querySelectorAll('.course-item');
    
    courseItems.forEach(item => {
        item.addEventListener('click', function() {
            const courseCode = this.querySelector('.course-code').textContent;
            const courseName = this.querySelector('.course-name').textContent;
            
            alert('Opening course: ' + courseCode + ' - ' + courseName);
            
            // In a real application:
            // window.location.href = 'course-details.html?code=' + courseCode;
        });
        
        // Add visual feedback on hover
        item.style.cursor = 'pointer';
    });

    // ========================================
    // SESSION ITEM CLICK (Optional Enhancement)
    // ========================================
    const sessionItems = document.querySelectorAll('.session-item');
    
    sessionItems.forEach(item => {
        item.addEventListener('click', function() {
            const sessionName = this.querySelector('.session-date').textContent;
            const sessionTime = this.querySelector('.session-time').textContent;
            
            alert('Session Details:\n' + sessionName + '\n' + sessionTime);
            
            // In a real application:
            // openSessionDetailsModal(sessionId);
        });
        
        // Add visual feedback on hover
        item.style.cursor = 'pointer';
    });

    // ========================================
    // CONSOLE LOG FOR DEBUGGING
    // ========================================
    console.log('Dashboard JavaScript loaded successfully!');
    console.log('Found ' + viewButtons.length + ' view buttons');
    console.log('Found ' + actionButtons.length + ' action buttons');
    console.log('Found ' + courseItems.length + ' course items');
    console.log('Found ' + sessionItems.length + ' session items');
});