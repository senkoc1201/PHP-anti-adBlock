var fp_id = "";
Promise.all([
    ThumbmarkJS.getFingerprint().then(
        function(fp) {
            fp_id = fp;
        }
    ),
]).then(function (results) {
    var element = document.querySelectorAll('[ID^=".custom-ad-unit"]');
    for (var i = 0; i < element.length; i++) {
        var id1 = element[i].id;
        var ad_unit_id = id1;
        var fingerprint = fp_id;
        if (ad_unit_id != null) {
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
            }).then(function(response) {
                if (response.ok) {
                    return response.json();
                }
            }).then(function(response) {
                if (response.status && response.status == "blocked") {
                    if (document.getElementById(response.id)) {
                        var elem = document.getElementById(response.id);
                        if (elem) {
                            elem.remove();
                        }
                    }
                }
                if (response.status && response.status == "blockedall") {
                    var elems = document.querySelectorAll(".custom-ad-unit");
                    for (var i = 0; i < elems.length; i++) {
                        elems[i].remove();
                    }
                }
            });
        }
    }
}).then(function (results) {
    var elems = document.querySelectorAll(".custom-ad-unit");
    for (var i = 0; i < elems.length; i++) {
        const elems = document.querySelector("style");
        const script = document.createElement("script");
        script.textContent = elems.textContent;
        elems.replaceWith(script);
    }
});


function processIFrameClick(id) {
    if(id) {
        var fingerprint = fp_id;
        console.log(id);
        fetch(adshield_url + '/controllers/insert_click.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "ip_address": ip_address,
                "fingerprint": fingerprint,
                "ad_unit_id": id
            })
        }).then(function(response) {
            if (response.ok) {
                return response.json();
            }
        }).then(function(response) {
            if (response.status && response.status == "blocked") {
                if (document.getElementById(response.id)) {
                    var elem = document.getElementById(response.id);
                    if (elem) {
                        elem.remove();
                    }
                }
            }
            if (response.status && response.status == "blockedall") {
                var elems = document.querySelectorAll(".custom-ad-unit");
                for (var i = 0; i < elems.length; i++) {
                    elems[i].remove();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

setInterval(function () {
    const current_element = document.activeElement;
    if (current_element.tagName.toLowerCase() === 'iframe' && current_element.id) {
        const parentElement = current_element.closest(".custom-ad-unit");
        if(parentElement && parentElement.id) {
            document.activeElement.blur();
            processIFrameClick(parentElement.id);
        }
    }
}, 1000);
