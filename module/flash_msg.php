<?php
if (isset($_SESSION['flash_type'])){
    $msg = $_SESSION['flash_msg'];
    if ($_SESSION['flash_type'] == "error"){
?>
<div class="flash_msg error">
    <img src="image/error.png"><span><?php echo $msg; ?></span>
</div>
<?php
    } else if ($_SESSION['flash_type'] == "success") {
?>
<div class="flash_msg success">
    <img src="image/success.png"><span><?php echo $msg; ?></span>
</div>
<?php
    } else if ($_SESSION['flash_type'] == "alert") {
?>
<div class="flash_msg alert">
    <img src="image/alert.png"><span><?php echo $msg; ?></span>
</div>
<?php
    }
    unset($_SESSION['flash_type']);
    unset($_SESSION['flash_msg']);
}

?>
<script type="text/javascript">
    $('div.flash_msg').unbind("click", function(){});
    $('div.flash_msg').click(function(){
        $(this).slideUp("slow");
    });
</script>