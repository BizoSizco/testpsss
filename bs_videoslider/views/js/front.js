document.addEventListener('DOMContentLoaded', function() {
    // Initialize Owl Carousel
    $('.bs-video-slider').each(function() {
        var settings = $(this).data('settings');
        $(this).owlCarousel({
            items: settings.items || 4,
            loop: settings.loop || true,
            nav: settings.nav || true,
            dots: settings.dots || false,
            responsive: {
                0: { items: 1 },
                576: { items: 2 },
                768: { items: 3 },
                992: { items: 4 }
            }
        });
    });

    // Video Play Functionality
    $('.play-button').on('click', function(e) {
        e.preventDefault();
        var videoContent = $(this).data('video');
        var videoModal = `
            <div class="bs-video-modal">
                <div class="modal-content">
                    <div class="embed-responsive embed-responsive-16by9">
                        ${videoContent}
                    </div>
                </div>
            </div>
        `;
        $('body').append(videoModal);
    });

    // Close Modal
    $(document).on('click', '.bs-video-modal', function(e) {
        if ($(e.target).hasClass('bs-video-modal')) {
            $(this).remove();
        }
    });
});