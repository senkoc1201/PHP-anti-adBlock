<!DOCTYPE html>
<html>
<head>
    <title>AdSense Click Test</title>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script async src="//securepubads.g.doubleclick.net/tag/js/gpt.js"></script>
    <?php include('./header.php'); ?>
</head>
<body>
<center><h3><a href="http://testsite.zone94.com/scripts/adshield2/adminpanel/index.php" target="_blank"> Click here to go to the Control Panel to reset the click count (Login: admin & admin)</a></h3></center>
<center><h3>Click on the right mouse button 3 times to hide the Ad Unit.</h3></center>
<div class="custom-ad-unit-1" style="display: flex; justify-content: center;">
    <div style="width: 100vw; display: flex; flex-wrap: wrap; justify-content: space-around;">
      <div style="max-width: 300px; max-height: 250px; padding: 5px;">
        <div id="gpt-passback">
          <style>
          window.googletag = window.googletag || {cmd: []};
          googletag.cmd.push(function() {
          googletag.defineSlot('/21988630651/Under_Top_Navigation_Bar_(Left)', [[300, 250], 'fluid'], 'gpt-passback').addService(googletag.pubads());
          googletag.enableServices();
          googletag.display('gpt-passback');
          });
          </style>
        </div>
      </div>
        <div id="w2g-slot5-cnt" style="max-width: 300px; max-height: 250px; padding: 5px;">
        <style>
             (function () {
                var domain = 'zone94.com';
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
          </style>
        </div>
    </div>
</div>
<center><h3>END</h3></center>
</div>
</body>
</html>
