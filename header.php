<?php
// Add this file with PHP include into the header of your website.
include_once 'config.php';
$ip_address = $_SERVER['REMOTE_ADDR'];
?>
<script>
    var ip_address = "<?php echo $ip_address; ?>";
    var adshield_url = "<?php echo $site_path; ?>";
</script>
<script src="<?php echo $site_path; ?>/js/thumbmark.umd.js"></script>
<script src="<?php echo $site_path; ?>/js/adshield.js"></script>
