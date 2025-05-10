<!DOCTYPE html>
<html>
<head>
    <title>AdSense Click Test</title>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script async src="//securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
        <script>
        // Function to remove only comment tags while keeping content
        function removeCommentTags() {
            const divs = document.querySelectorAll('div');
            divs.forEach(div => {
                const content = div.innerHTML;
                if (content.includes('<!--')) {
                    // Remove only the comment tags, keeping the content
                    div.innerHTML = content
                        .replace(/<!--/g, '')  // Remove opening comment tag
                        .replace(/-->/g, '');  // Remove closing comment tag
                }
            });
        }
        document.addEventListener('DOMContentLoaded', removeCommentTags);
    </script>
    <?php include('./header.php'); ?>
</head>
<body>
<center><h3><a href="http://testsite.zone94.com/scripts/adshield2/adminpanel/index.php" target="_blank"> Click here to go to the Control Panel to reset the click count (Login: admin & admin)</a></h3></center>
<center><h3>Click on the right mouse button 3 times to hide the Ad Unit.</h3></center>
<div style="text-align: center;">
    <h2>Ad Unit 1 (AdSense)</h2>
    <div class="custom-ad-unit-1">
<!--
    <div>
        <ins class="adsbygoogle"
            style="display:inline-block;width:300px;height:250px"
            data-ad-client="ca-pub-2514525490159799"
            data-ad-slot="3339058173">
        </ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </div>    
-->
</div>
<div style="text-align: center;">
    <h2>Ad Unit 2 (GAM)</h2>
	<div class="custom-ad-unit-2">
<!--
    <div id="gpt-passback">
        <script>
            window.googletag = window.googletag || {cmd: []};
            googletag.cmd.push(function() {
            googletag.defineSlot('/21988630651/Under_Top_Navigation_Bar_(Right)', [[300, 250], [200, 200], [250, 250], 'fluid'], 'gpt-passback').addService(googletag.pubads());
            googletag.enableServices();
            googletag.display('gpt-passback');
            });
        </script>
    </div>
-->
    </div>
</div>
<center><h3>END</h3></center>
</div>
</body>
</html>
