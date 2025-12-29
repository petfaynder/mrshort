/**
 * MrShort WordPress Admin Scripts
 */
jQuery(document).ready(function ($) {

    // Test connection button
    $('#mrshort_test_connection').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var $status = $('#mrshort_connection_status');

        $btn.prop('disabled', true).text('Testing...');
        $status.html('');

        $.ajax({
            url: mrshort_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'mrshort_test_connection',
                nonce: mrshort_admin.nonce
            },
            success: function (response) {
                if (response.success) {
                    $status.html('<span style="color:green;">✓ Connection successful!</span>');
                } else {
                    $status.html('<span style="color:red;">✗ ' + response.data + '</span>');
                }
            },
            error: function () {
                $status.html('<span style="color:red;">✗ Connection failed</span>');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Test Connection');
            }
        });
    });

});
