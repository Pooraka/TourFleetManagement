<?php
$url="";
if(!empty($_SERVER["https"])){
    $url="https://";
    }
else{
    $url="http://";
}

$hostname=$_SERVER["HTTP_HOST"];

$url.=$hostname;
$url.="/onlineclothesstore/view/login.php";

?>

<script>
    window.location="<?php echo $url; ?>";
</script>

<?php
