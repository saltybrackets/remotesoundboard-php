<html><head>
<link rel=stylesheet href="jqueryFileTree.css" type="text/css">
<script src="jquery.js"></script>
<script src="jqueryFileTree.js"></script>

<script type="text/javascript">
$(document).ready( function() {
    $('#test').fileTree({ root: '../' }, function(file) {
        alert(file);
    });
});
</script>

</head>

<body>
<div name=test id=test>
</div>



</body>