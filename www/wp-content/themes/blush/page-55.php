<?
/** This page displays the "Ask A Question" form in a popup window. */
?><!DOCTYPE html>
<html>
<head>
    <? gravity_form_enqueue_scripts(2, true); ?>
</head>
<body <?php body_class(); ?>>
<div class="modal fade gravity-modal" id="ask-question-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Join Our Newsletter</h4>
                <p>Stay in the loop! We'll send you fun updates, discounts, and more!</p>
            </div>
            <div class="modal-body"><?php gravity_form(2, false, false, false, '', true); ?></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>