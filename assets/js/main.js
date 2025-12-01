// Modern JavaScript for PinePix
// Enhanced UI interactions and functionality

// Smooth page transitions
(function() {
    // Add fade-in animation to body on page load
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.3s ease-in-out';
    
    window.addEventListener('load', function() {
        document.body.style.opacity = '1';
    });
    
    // Intercept all internal links for smooth transitions
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;
        
        const href = link.getAttribute('href');
        const target = link.getAttribute('target');
        
        // Only handle internal links without target="_blank"
        // Check if it's a relative path or same origin
        const isInternalLink = href && (
            href.startsWith(window.location.origin) || 
            href.startsWith('/') || 
            !href.includes('://')
        );
        
        if (isInternalLink && !target && !href.includes('#') && !link.hasAttribute('data-no-transition')) {
            // Skip if it's a form submission link, special link, or external link
            if (link.onclick || link.classList.contains('btn-delete') || link.hasAttribute('data-no-transition')) return;
            
            // Skip if modifier keys are pressed
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            
            e.preventDefault();
            
            // Add loading indicator
            const mainContent = document.querySelector('.flex-1');
            if (mainContent) {
                mainContent.style.opacity = '0.7';
                mainContent.style.transition = 'opacity 0.15s ease-in-out';
            }
            
            // Navigate with slight delay for smooth transition
            setTimeout(function() {
                window.location.href = href;
            }, 150);
        }
    });
})();

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables with modern styling and responsive features
    if (typeof $.fn.DataTable !== 'undefined') {
        $('.data-table').each(function() {
            const table = $(this);
            const isMobile = window.innerWidth < 640;
            
            table.DataTable({
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    },
                    breakpoints: [
                        { name: 'desktop', width: Infinity },
                        { name: 'tablet', width: 1024 },
                        { name: 'mobile', width: 640 }
                    ]
                },
                pageLength: isMobile ? 5 : 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    emptyTable: "No data available in table",
                    zeroRecords: "No matching records found"
                },
                dom: isMobile 
                    ? '<"flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-2 sm:space-y-0"<"w-full sm:w-auto"l><"w-full sm:w-auto"f>>rt<"flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4 space-y-2 sm:space-y-0"<"w-full sm:w-auto"i><"w-full sm:w-auto"p>>'
                    : '<"flex flex-row justify-between items-center mb-4"<"w-auto"l><"w-auto"f>>rt<"flex flex-row justify-between items-center mt-4"<"w-auto"i><"w-auto"p>>',
                drawCallback: function() {
                    // Add fade-in animation to table rows
                    $(this.api().table().body()).find('tr').addClass('fade-in');
                },
                // Mobile-optimized column visibility
                columnDefs: [
                    {
                        responsivePriority: 1,
                        targets: 0 // First column always visible
                    },
                    {
                        responsivePriority: 2,
                        targets: -1 // Last column (actions) always visible
                    }
                ]
            });
        });
        
        // Handle window resize for DataTables
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                $('.data-table').each(function() {
                    if ($.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable().columns.adjust().responsive.recalc();
                    }
                });
            }, 250);
        });
    }
    
    // Auto-dismiss alerts with fade animation
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
    
    // Enhanced delete confirmations with SweetAlert2
    $(document).on('click', '.btn-delete', function(e) {
        const button = $(this);
        
        // Allow custom delete handlers (farm/shop/announcements) to handle their own SweetAlert + form submit
        if (button.attr('data-no-global-delete') === 'true') {
            return;
        }
        
        e.preventDefault();
        const url = button.closest('form').attr('action') || button.attr('href');
        const form = button.closest('form');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                buttonsStyling: true,
                customClass: {
                    popup: 'rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form.length) {
                        form.submit();
                    } else if (url) {
                        window.location.href = url;
                    }
                }
            });
        } else {
            // Fallback to native confirm
            if (confirm('Are you sure you want to delete this?')) {
                if (form.length) {
                    form.submit();
                } else if (url) {
                    window.location.href = url;
                }
            }
        }
    });
    
    // Image preview with smooth transitions
    $('input[type="file"]').on('change', function() {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewId = $(input).data('preview');
                let previewImg;
                
                if (previewId) {
                    previewImg = $(previewId);
                } else {
                    previewImg = $(input).siblings('.image-preview');
                }
                
                if (previewImg.length === 0) {
                    previewImg = $('<img>', {
                        class: 'image-preview',
                        style: 'display: block;'
                    });
                    $(input).after(previewImg);
                }
                
                previewImg.attr('src', e.target.result)
                    .hide()
                    .fadeIn(300);
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
    
    // Add loading states to forms (skip if form has confirmation handler)
    $('form').on('submit', function(e) {
        const form = $(this);
        
        // Skip if form has confirmation handler (marked with data-has-confirmation)
        if (form.attr('data-has-confirmation') === 'true') {
            return;
        }
        
        const submitBtn = form.find('button[type="submit"]');
        
        if (submitBtn.length && !form.data('submitting')) {
            form.data('submitting', true);
            submitBtn.prop('disabled', true);
            
            const originalText = submitBtn.html();
            submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
            
            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitBtn.prop('disabled', false).html(originalText);
                form.data('submitting', false);
            }, 10000);
        }
    });
    
    // Enhanced tooltips
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Modern card hover effects
    $('.card').each(function() {
        $(this).on('mouseenter', function() {
            $(this).addClass('shadow-lg');
        }).on('mouseleave', function() {
            $(this).removeClass('shadow-lg');
        });
    });
    
    // Animate elements on scroll (Intersection Observer)
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.card, .alert').forEach(el => {
            observer.observe(el);
        });
    }
    
    // Enhanced modal animations
    $('.modal').on('show.bs.modal', function() {
        const modal = $(this);
        modal.find('.modal-dialog').css({
            transform: 'scale(0.8)',
            opacity: 0
        });
    });
    
    $('.modal').on('shown.bs.modal', function() {
        const modal = $(this);
        modal.find('.modal-dialog').css({
            transform: 'scale(1)',
            opacity: 1,
            transition: 'all 0.3s ease-out'
        });
        
    });
    
    
    // Copy to clipboard functionality
    $('.copy-to-clipboard').on('click', function(e) {
        e.preventDefault();
        const text = $(this).data('copy') || $(this).text();
        
        navigator.clipboard.writeText(text).then(function() {
            showToast('Copied to clipboard!', 'success');
        });
    });
});

// Toast notification helper using SweetAlert2
function showToast(message, type = 'success', duration = 3000) {
    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: duration,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        Toast.fire({
            icon: type,
            title: message
        });
    } else if (typeof toast !== 'undefined') {
        // Use window.toast if available
        toast[type](message, { duration: duration });
    } else {
        // Fallback to alert
        alert(message);
    }
}

// AJAX helper with error handling
function ajaxRequest(url, data, method = 'POST') {
    return $.ajax({
        url: url,
        method: method,
        data: typeof data === 'object' ? JSON.stringify(data) : data,
        dataType: 'json',
        contentType: method === 'POST' ? 'application/json' : 'application/x-www-form-urlencoded',
        processData: method === 'POST' && typeof data === 'object' ? false : true
    }).fail(function(xhr, status, error) {
        console.error('AJAX Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    });
}

// Form validation helper
function validateForm(formSelector) {
    const form = $(formSelector);
    let isValid = true;
    
    form.find('input[required], select[required], textarea[required]').each(function() {
        const field = $(this);
        if (!field.val().trim()) {
            field.addClass('is-invalid');
            isValid = false;
        } else {
            field.removeClass('is-invalid');
        }
    });
    
    return isValid;
}

// Debounce function for search inputs
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Format numbers with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Format currency
function formatCurrency(amount, currency = 'RM') {
    return currency + ' ' + formatNumber(parseFloat(amount).toFixed(2));
}

// Initialize ApexCharts theme
if (typeof ApexCharts !== 'undefined') {
    ApexCharts.defaults.theme = {
        mode: 'light',
        palette: 'palette1',
        monochrome: {
            enabled: false,
            color: '#6366f1',
            shadeTo: 'light',
            shadeIntensity: 0.65
        }
    };
}

// Test function to verify libraries are loaded
window.testLibraries = function() {
    console.log('=== Library Check ===');
    console.log('jQuery:', typeof $ !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
    console.log('Bootstrap:', typeof bootstrap !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
    console.log('DataTables:', typeof $.fn.DataTable !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
    console.log('SweetAlert2:', typeof Swal !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
    console.log('Toast:', typeof toast !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
    console.log('ApexCharts:', typeof ApexCharts !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
    console.log('Leaflet:', typeof L !== 'undefined' ? '✓ Loaded' : '✗ Not loaded');
};

console.log('PinePix Modern UI Loaded Successfully!');
console.log('Run testLibraries() in console to check all libraries');