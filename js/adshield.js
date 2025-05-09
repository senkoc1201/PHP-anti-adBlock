var fp_id = "";
var adshield_url = window.adshield_url || ''; // Get from global variable or default to empty
var ip_address = window.ip_address || ''; // Get from global variable or default to empty
// console.log("advert ran------------");
// Step 1: Get fingerprint
ThumbmarkJS.getFingerprint().then(function(fp) {
    fp_id = fp;

    // Step 2: Select all elements with class starting with "custom-ad-unit"
    var elements = document.querySelectorAll('[class^="custom-ad-unit"]');

    elements.forEach(function(element) {
        var ad_unit_class = Array.from(element.classList).find(cls => cls.startsWith("custom-ad-unit"));
        var fingerprint = fp_id;

        if (ad_unit_class) {
            fetch(adshield_url + '/controllers/block_check.php', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "ip_address": ip_address,
                    "fingerprint": fingerprint,
                    "ad_unit_id": ad_unit_class // use class name as ID
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return text ? JSON.parse(text) : null;
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        return null;
                    }
                });
            })
            .then(function(response) {
                if (!response) return;

                if (response.status === "blocked" && response.id) {
                    var elem = document.querySelector("." + response.id);
                    if (elem) elem.remove();
                }

                if (response.status === "blockedall") {
                    var elems = document.querySelectorAll('[class^="custom-ad-unit"]');
                    elems.forEach(e => e.remove());
                }
            })
            .catch(function(error) {
                console.error('Error processing ad unit:', error);
            });
        }
    });
}).catch(function(error) {
    console.error('Error in fingerprint processing:', error);
});

// Function to handle iframe clicks
function processIFrameClick(className) {
    if (className) {
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
                "ad_unit_id": className
            })
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return text ? JSON.parse(text) : null;
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    return null;
                }
            });
        })
        .then(function(response) {
            if (!response) return;

            if (response.status === "blocked" && response.id) {
                var elem = document.querySelector("." + response.id);
                if (elem) elem.remove();
            }

            if (response.status === "blockedall") {
                var elems = document.querySelectorAll('[class^="custom-ad-unit"]');
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
        const parentElement = current_element.closest('[class^="custom-ad-unit"]');
        if (parentElement) {
            const ad_class = Array.from(parentElement.classList).find(cls => cls.startsWith("custom-ad-unit"));
            if (ad_class) {
                document.activeElement.blur();
                processIFrameClick(ad_class);
            }
        }
    }
}, 1000);
