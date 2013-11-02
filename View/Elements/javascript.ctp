<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.js" type="text/javascript"></script>
<script type="text/javascript">
    var tokens = JSON.parse('<?php echo json_encode($tokens); ?>');
    var validation = <?php echo $validation; ?>;
</script>
<script src="/ApiManager/js/api.js" type="text/javascript"></script>