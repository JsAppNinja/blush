<?
/** This page displays the "Tell Us Your Story" form for adding testimonials in a popup window. */
?><!DOCTYPE html>
<html>
<head>
    <? gravity_form_enqueue_scripts(5, true); ?>
</head>
<body <?php body_class(); ?>>
<div class="modal fade gravity-modal" id="apply-job-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Apply for a Job at Blush</h4>
                <p>Tell us why you'd be a great fit</p>
            </div>
            <div class="modal-body"><?php gravity_form(5, false, false, false, '', true); ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>