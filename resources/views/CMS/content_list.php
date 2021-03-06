<?php
require('_top.php');
?>
<main role="main" class="container">
	<div class="jumbotron">
        <div class="mx-auto">
            <h2><span class="glyphicon <?=$selected->menu_icon?>" aria-hidden="true"></span> <?=$selected->menu_title?></h2>
            <p>This example is a quick exercise to illustrate how the navbar and its contents work. Some navbars extend the width of the viewport, others are confined within a <code>.container</code>. For positioning of navbars, checkout the <a href="../navbar-top/">top</a> and <a href="../navbar-top-fixed/">fixed top</a> examples.</p>
            <p>At the smallest breakpoint, the collapse plugin is used to hide the links and show a menu button to toggle the collapsed content.</p>
            <p><a class="btn btn-primary" href="../../components/navbar/" role="button">View navbar docs »</a></p>
        </div>
    </div>
</main>
<?php
require('_bottom.php');
?>