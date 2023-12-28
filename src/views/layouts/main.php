<!DOCTYPE html>
<html lang="en">
    
    <?php include("header.php"); ?>
    <?php include("scripts_top.php"); ?>

        <div class="main-container" id="container">

           <div class="overlay"></div>
           <div class="search-overlay"></div>
             <?php include("sidenav.php"); ?>
             <?php print_r($this->content) ?>
             <?php include("footer.php"); ?>
        </div> 

    
        <?php include("scripts_bot.php"); ?>

<script type="text/javascript">

    updateLastSeen();

    function updateLastSeen(){

        $.post('<?= BASEURL; ?>Admin/UpdateLastSeen/');
    }

    setInterval(function () {updateLastSeen()}, 60000);

</script>

</body>

</html>