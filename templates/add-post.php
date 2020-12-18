
<?php include($_SERVER['DOCUMENT_ROOT']."/header.php");

        error_reporting(E_ALL ^ E_WARNING);     
        spl_autoload_register(function ($class_name) {
            include  $_SERVER['DOCUMENT_ROOT'].'/modules/Post/'.$class_name . '.php';
            include  $_SERVER['DOCUMENT_ROOT'].'/modules/Category/'.$class_name . '.php';
            include  $_SERVER['DOCUMENT_ROOT'].'/modules/User/'.$class_name . '.php';
            include  $_SERVER['DOCUMENT_ROOT'].'/includes/classes/'.$class_name . '.php';
        });
        $post = new Post();

    ?>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT']."/templates/navigation/navigation.php");?>

    <div class="main-wrapper">
	    	    
	    <article class="add-post-section py-5">
		    <div class="container">
            <form class="add-post-form">
            <div class="form-group">
                <label >Post title</label>
                <input type="text" class="form-control title" aria-describedby="emailHelp" placeholder="Enter title">
                <small id="title" class="form-text text-muted">Think of something, that will encourage people to read it!</small>
            </div>
            <div class="form-group">
                <textarea class="form-control content" rows="20" ></textarea>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary ">Add post</button>
            </div>
            
            </form>
        
			    
		    </div>
	    </article>
       

        <?php include ($_SERVER['DOCUMENT_ROOT']."/scripts.php");?>
</body>


</html>
