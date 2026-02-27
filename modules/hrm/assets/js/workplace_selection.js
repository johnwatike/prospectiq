(function() {
    "use strict";

    // Check if workplace is missing and show dialog
    function checkWorkplaceAndShowDialog() {
        // Get workplace_id from PHP variable passed to JavaScript
        var workplaceId = typeof staff_workplace_id !== 'undefined' ? staff_workplace_id : 0;
        
        // Check if workplace is missing (null, 0, empty, or undefined)
        if (!workplaceId || workplaceId === null || workplaceId === 0 || workplaceId === '' || workplaceId === '0') {
            // Small delay to ensure modal is in DOM
            setTimeout(function() {
                if ($('#workplace_selection_modal').length > 0) {
                    $('#workplace_selection_modal').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                }
            }, 500);
        }
    }

    // Handle workplace selection form submission
    function saveWorkplace() {
        var workplace_id = $('#workplace_id').val();
        
        if (!workplace_id || workplace_id === '') {
            alert_float('danger', 'Please select a workplace');
            return;
        }

        // Disable button during submission
        var $btn = $('#save_workplace_btn');
        $btn.prop('disabled', true);
        $btn.html('<i class="fa fa-spinner fa-spin"></i> ' + 'Saving...');

        $.ajax({
            url: admin_url + 'hrm/update_staff_workplace',
            type: 'POST',
            data: {
                workplace_id: workplace_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert_float('success', response.message);
                    $('#workplace_selection_modal').modal('hide');
                    // Reload page to update session and header
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert_float('danger', response.message);
                    $btn.prop('disabled', false);
                    $btn.html('<i class="fa fa-check"></i> ' + 'Save');
                }
            },
            error: function() {
                alert_float('danger', 'An error occurred while saving workplace');
                $btn.prop('disabled', false);
                $btn.html('<i class="fa fa-check"></i> ' + 'Save');
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        // Check workplace on page load
        checkWorkplaceAndShowDialog();

        // Also check after window fully loads (in case of delayed content)
        $(window).on('load', function() {
            checkWorkplaceAndShowDialog();
        });

        // Handle save button click
        $(document).on('click', '#save_workplace_btn', function(e) {
            e.preventDefault();
            saveWorkplace();
        });

        // Prevent modal from being closed without selection
        $('#workplace_selection_modal').on('hide.bs.modal', function(e) {
            var workplace_id = $('#workplace_id').val();
            if (!workplace_id || workplace_id === '' || workplace_id === null) {
                e.preventDefault();
                alert_float('warning', 'Please select a workplace before continuing');
                return false;
            }
        });
    });
})();
