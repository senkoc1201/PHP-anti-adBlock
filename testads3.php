<!DOCTYPE html>
<html>
<head>
    <title>AdSense Click Test</title>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script async src="//securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
    <script>
        // Function to remove only comment tags while keeping content
        function removeCommentTagsAndRunScripts() {
            const divs = document.querySelectorAll('div');
            divs.forEach(div => {
                let content = div.innerHTML;
                if (content.includes('<!--')) {
                    // Remove only the comment tags, keeping the content
                    content = content.replace(/<!--/g, '').replace(/-->/g, '');
                    div.innerHTML = content;

                    // Find all script tags in the new content
                    const scripts = div.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        // Copy attributes
                        for (let attr of oldScript.attributes) {
                            newScript.setAttribute(attr.name, attr.value);
                        }
                        // Copy inline script content
                        newScript.text = oldScript.text;
                        // Replace old script with new one (which will execute)
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                }
            });
        }
        document.addEventListener('DOMContentLoaded', removeCommentTagsAndRunScripts);
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
        <ins class="adsbygoogle"
            style="display:inline-block;width:300px;height:250px"
            data-ad-client="ca-pub-2514525490159799"
            data-ad-slot="3339058173">
        </ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
-->
    </div>
</div>
<div style="text-align: center;">
    <h2>Ad Unit 2 (GAM)</h2>
    <div class="custom-ad-unit-2">
<!--
        <script>
             (function () {
                var domain = '1337.com';
                var slot = 'w2g-slot5';
                if (window.self !== window.parent) {
                  var d = top.document, w = window.parent;
                   var parent = this.frameElement;
                   parent.style.display = "none";
               } else {
                   var d = document, w = window, parent = null;
                   if (typeof d.currentScript !== 'undefined') {
                      parent = d.currentScript;
                      if (parent == null) {
                         parent = document.getElementById(slot + '-cnt');
                      }
                   } else {
                      parent = d.body.lastElementChild;
                   }
                }
                d.addEventListener('wtgLoaded', function (e) {
                   if (typeof w.w2g.single === 'function') {
                      w.w2g.single(domain, slot, parent);
                   }
                }, false);
                if (w.w2gLoaded === undefined) {
                   w.w2gLoaded = 0;
                }
                if (w.w2gLoaded < 1 && w.w2g === undefined) {
                   var element = d.createElement('script'), head = d.head || d.getElementsByTagName('head')[0];
                   element.type = 'text/javascript';
                   element.async = true;
                   element.src = 'https://lib.wtg-ads.com/lib.single.wtg.min.js';
                   head.appendChild(element);
                   w.w2gLoaded++;
                }
                if (w.w2g !== undefined && typeof w.w2g.single === 'function') {
                   w.w2g.single(domain, slot, parent);
                }
             })();
          </script>
-->
    </div>
</div>
<center><h3>END</h3></center>
</div>
</body>
</html>
