<?
/** This page displays the "Tell Us Your Story" form for adding testimonials in a popup window. */
?><!DOCTYPE html>
<html>
<head>
    <? gravity_form_enqueue_scripts(3, true); ?>
</head>
<body <?php body_class(); ?>>
<div class="modal fade gravity-modal" id="tell-story-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tell Us Your Story</h4>
                <p>Tell Us Your Story! How has Blush helped you?</p>
            </div>
            <div class="modal-body"><?php gravity_form(3, false, false, false, '', true); ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>