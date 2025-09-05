// Custom Front-end JavaScript

$(document).ready(function() {
    console.log('Beezu Framework - Front-end loaded');
    
    // Add smooth scrolling to anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 1000);
        }
    });
    
    // Add loading states to forms
    $('form').on('submit', function() {
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Loading...');
    });
});
