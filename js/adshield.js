var fp_id = "";
// console.log("advert ran------------");
// Step 1: Get fingerprint
ThumbmarkJS.getFingerprint().then(function(fp) {
    fp_id = fp;

    // Select all ad units by class or id
    var elements = document.querySelectorAll('[class^="custom-ad-unit"], [id^="custom-ad-unit"]');

    elements.forEach(function(element) {
        // Prefer id if present, else use class
        var ad_unit_id = element.id ? element.id : Array.from(element.classList).find(cls => cls.startsWith("custom-ad-unit"));
        var fingerprint = fp_id;

        if (ad_unit_id) {
            fetch(adshield_url + '/controllers/block_check.php', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "ip_address": ip_address,
                    "fingerprint": fingerprint,
                    "ad_unit_id": ad_unit_id
                })
            })
            .then(function(response) {
                if (response.ok) {
                    return response.json();
                }
            })
            .then(function(response) {
                if (!response) return;

                // Try to remove by id first, then by class
                if (response.status === "blocked" && response.id) {
                    var elem = document.getElementById(response.id) ||
                               document.querySelector("." + response.id);
                    if (elem) elem.remove();
                }

                if (response.status === "blockedall") {
                    var elems = document.querySelectorAll('[class^="custom-ad-unit"], [id^="custom-ad-unit"]');
                    elems.forEach(e => e.remove());
                }
            });
        }
    });
})
.then(function () {
    // Replace <style> with <script> inside each ad unit
    var adUnits = document.querySelectorAll('[class^="custom-ad-unit"], [id^="custom-ad-unit"]');
    adUnits.forEach(function(unit) {
        var styleElem = unit.querySelector("style");
        if (styleElem) {
            var script = document.createElement("script");
            script.textContent = styleElem.textContent;
            styleElem.replaceWith(script);
        }
    });
});

// Function to handle iframe clicks
function processIFrameClick(ad_unit_id) {
    if (ad_unit_id) {
        var fingerprint = fp_id;
        fetch(adshield_url + '/controllers/insert_click.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "ip_address": ip_address,
                "fingerprint": fingerprint,
                "ad_unit_id": ad_unit_id
            })
        }).then(function(response) {
            if (response.ok) {
                return response.json();
            }
        }).then(function(response) {
            if (!response) return;

            // Try to remove by id first, then by class
            if (response.status === "blocked" && response.id) {
                var elem = document.getElementById(response.id) ||
                           document.querySelector("." + response.id);
                if (elem) elem.remove();
            }

            if (response.status === "blockedall") {
                var elems = document.querySelectorAll('[class^="custom-ad-unit"], [id^="custom-ad-unit"]');
                elems.forEach(e => e.remove());
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Monitor iframe clicks every 1s
setInterval(function () {
    const current_element = document.activeElement;
    if (current_element.tagName.toLowerCase() === 'iframe') {
        const parentElement = current_element.closest('[class^=\"custom-ad-unit\"], [id^=\"custom-ad-unit\"]');
        if (parentElement) {
            var ad_unit_id = parentElement.id ? parentElement.id : Array.from(parentElement.classList).find(cls => cls.startsWith("custom-ad-unit"));
            if (ad_unit_id) {
                document.activeElement.blur();
                processIFrameClick(ad_unit_id);
            }
        }
    }
}, 1000);
