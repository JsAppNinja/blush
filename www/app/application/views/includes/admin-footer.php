</div>
</div>

<div id="footer">
</div>
<script type="text/javascript">
app.user = <?= get_user_json() ?>;
app.data = {price_counseling: <?=PRICE_COUNSELING?>, price_diary: <?=PRICE_DIARY ?>};
app.activeRouter = '<?= $activeRouter ?>';
</script>
<? if(IS_TEST) {
    include(APPPATH.'views/includes/assets-footer.html');
} else {
    include(APPPATH.'views/includes/prod/assets-footer.html');
} ?>

<script src="/app/partials/all" type="text/javascript"></script>
</body>
</html>