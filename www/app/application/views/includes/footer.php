</div>
</div>

<div id="footer"></div>
<script type="text/javascript">
    app.user = <?= get_user_json() ?>;
    app.data = <?= get_blush_data() ?>;
    <? if(isset($activeRouter)) { ?>
    app.activeRouter = '<?= $activeRouter ?>';
    <? } ?>

    <? if(get_user_id()) { ?>
        window._fbq.push(['track', '6030584888798', {'value':'0.00','currency':'USD'}]);
    <? } ?>
</script>
<? if (IS_TEST) {
    include(APPPATH.'views/includes/assets-footer.html');
} else {
    include(APPPATH.'views/includes/prod/assets-footer.html');
} ?>

<script src="/app/partials/all" type="text/javascript"></script>
<script src="https://js.stripe.com/v2/" type="text/javascript"></script>
<script type="text/javascript">Stripe.setPublishableKey('<?=$this->config->item('stripe_public_key')?>');</script>
<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
</body>
</html>