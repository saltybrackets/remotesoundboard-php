<html><head>
<link rel=stylesheet href="jqueryFileTree.css" type="text/css">
<script src="jquery.js"></script>
<script src="jqueryFileTree.js"></script>

<script type="text/javascript">

// http://www.abeautifulsite.net/blog/2008/03/jquery-file-tree/
$(document).ready( function() {
    $('#listSounds').fileTree({
        root: 'Sounds',
        script: 'jqueryFileTree.php',
        expandSpeed: 1000,
        collapseSpeed: 1000,
        multiFolder: false
    }, function(file) {
        alert(file);
    });
});
</script>

</head>

<body>
<div name=test id=test>
</div>



</body>