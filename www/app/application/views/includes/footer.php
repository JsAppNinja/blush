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
<script type="text/javascript">(function () {
                            if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
                            if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
                                var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                s.type = 'text/javascript';s.charset = 'UTF-8';s.async = true;
                                s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
                                var h = d[g]('body')[0];h.appendChild(s); }})();
                    </script>


<?php 
    // get the user
    $user = get_user();
    // call the key for intercom
    $key = $this->config->item('intercom_key');
    echo $key;
 ?>

<script>
  window.intercomSettings = {
    app_id: "w7qc2x21",
    name: '<?php $user = get_user(); echo $user->firstname . " " . $user->lastname ?>',
    email: '<?php $user = get_user(); echo $user->email; ?>',
    user_hash: "<?php
      echo hash_hmac(
        'sha256',
        $user->email,
        $key
      );
    ?>" // HMAC using SHA-25
  };
</script>

<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/w7qc2x21';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
</body>
</html>