$(document).ready(function(){
    // Sortable Videos
    $('.sortable').sortable({
        axis: 'y',
        handle: '.sortable-handle',
        update: function(event, ui) {
            var positions = [];
            $('.sortable tr').each(function(index) {
                positions.push({
                    id: $(this).attr('id').replace('video_', ''),
                    position: index + 1
                });
            });

            $.ajax({
                type: 'POST',
                url: ajax_url,
                data: {
                    action: 'updatePositions',
                    positions: positions
                },
                success: function(response) {
                    if(response.success) {
                        showSuccessMessage('Positions updated successfully');
                    }
                }
            });
        }
    });
    
    // Delete Video Confirmation
    $(document).on('click', '.delete-video', function(e) {
        e.preventDefault();
        var videoId = $(this).data('id');
        if (confirm(delete_confirmation_text)) {
            window.location.href = delete_url + '&id=' + videoId;
        }
    });
});