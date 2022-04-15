<style>
    .dygraph-label {
        text-align: center;
    }
    .dygraph-ylabel {
        transform: rotate(-90deg);
    }
</style>
<script type="text/javascript"
        src="<?= $assetsUri; ?>dygraph.min.js"></script>
<link rel="stylesheet" src="<?= $assetsUri; ?>dygraph.css" />
<div id="graphdiv"></div>
<script type="text/javascript">
    g = new Dygraph(

        // containing div
        document.getElementById("graphdiv"),

        <?php echo '"'.$assetsOutput.'"'; ?>,
        <?php echo $graphOptions; ?>

    );
</script>