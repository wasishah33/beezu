// Custom Admin JavaScript

$(document).ready(function() {
    console.log('Beezu Framework - Admin panel loaded');
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Add confirmation dialogs for delete actions
    $('.btn-danger').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });
    
    // Auto-refresh stats every 30 seconds
    setInterval(function() {
        $.get('/admin/api/stats', function(data) {
            if (data.status === 'success') {
                // Update stats in dashboard if present
                $('.small-box .inner h3').each(function(index) {
                    var values = Object.values(data.data);
                    if (values[index]) {
                        $(this).text(values[index]);
                    }
                });
            }
        });
    }, 30000);
    
    // Form validation
    $('form').on('submit', function(e) {
        var isValid = true;
        $(this).find('input[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
