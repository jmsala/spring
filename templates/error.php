<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="jumbotron">
          <h2 class="display-2">404 Page Not Found</h2>
          <?php
          if (isset($error_msg)):
            echo "<p class='learn'>".$error_msg."</p>";
          else:
            echo "<p class='learn'>The page you requested could not be found, either contact your webmaster or try again. Use your browsers <b>Back</b> button to navigate to the page you have prevously come from</p>
          <p><b>Or you could just press this neat little button:</b></p>";
          endif;
          ?>
          <a href="<?php echo BASE_URL ?>" class="btn btn-large btn-info"><i class="icon-home icon-white"></i> Take Me Home</a>
        </div>
    </div>
  </div>
</div>
