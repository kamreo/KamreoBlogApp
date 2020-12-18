
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
	    
	    <section class="blog-list px-3 py-5 p-md-5">
		    <div class="container">
			<?php 
					$posts = Post::SelectAll();
					while($row = mysqli_fetch_array($posts)){
						echo
						   '<div class="item mb-5">
								<div class="media">
									<img class="mr-3 img-fluid post-thumb d-none d-md-flex" src="/assets/images/blog/blog-post-thumb-7.jpg" alt="image">
									<div class="media-body">
										<h3 class="title mb-1"><a href="blog-post.html">'. $row['title'].'</a></h3>
										<div class="meta mb-1"><span class="date">'.$row['date'].'</span><span class="time">5 min read</span><span class="comment"><a href="#">4 comments</a></span></div>
										<div class="intro">'. $row['content'].'</div>
										<a class="more-link" href="blog-post.html">Read more &rarr;</a>
									</div><!--//media-body-->
								</div><!--//media-->
							</div><!--//item-->';
						
						
					}
				?>
			    
			    <nav class="blog-nav nav nav-justified my-5">
				  <a class="nav-link-prev nav-item nav-link d-none rounded-left" href="#">Previous<i class="arrow-prev fas fa-long-arrow-alt-left"></i></a>
				  <a class="nav-link-next nav-item nav-link rounded" href="/templates/blog-list.php">Next<i class="arrow-next fas fa-long-arrow-alt-right"></i></a>
				</nav>
				
		    </div>
	    </section>

    </div><!--//main-wrapper-->

</body>
</html> 

