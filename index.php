<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PANTHEON DNS Verification</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="docs/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        background: url("docs/assets/img/grid-18px-masked.png") repeat scroll 0 0 transparent;
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .hero-unit .centered{
        text-align: center;
      }
      #hostname{
        float: left;
      } 
    </style>
    <link href="docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="docs/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="docs/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="docs/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

 <?php 
  $site_url = "dnscheck.gotpantheon.com"; 
  $status = "";
  $config_errors = array();
  $config_errors["A"]["match"] = FALSE; 
  $config_errors["CNAME"]["match"] = FALSE; 
  if(!empty($_GET['hostname'])){
    $hostname = $_GET['hostname']; 
    $site_url = $hostname ;
    $dns_record = dns_get_record($hostname, DNS_ALL);  
    $status = "fail";
    $pantheon_load_balancers = array(
      "50.56.49.247",
      "50.56.49.215"
    );
    $pantheon_edge_servers = array(
      "edge.live.getpantheon.com"
     );    
    
    foreach( $dns_record as $host_info ) { 
      if($host_info['type'] == "A"){
	if(in_array($host_info['ip'], $pantheon_load_balancers)){
	  $status = "pass"; 
	  $config_errors["A"]["match"] = TRUE; 
	  $config_errors["A"]["host"] = $host_info; 
	}
	else{
	  $config_errors["A"]["list"][] = $host_info; 
	}
      }
      
      if($host_info['type'] == "CNAME"){
	if(in_array($host_info['target'], $pantheon_edge_servers)){
	  $status = "pass"; 
	  $config_errors["CNAME"]["match"] = TRUE; 
	  $config_errors["CNAME"]["host"] = $host_info; 
	}  
	else{
	  $config_errors["CNAME"]["list"][] = $host_info;
	}   
      }
    }
    
    if(isset($_GET['debug'])){
      //print "<pre>"; print_r($dns_record);  print "</pre>";
      print "<pre>"; print_r($config_errors);  print "</pre>";
    }

  } 
?>
<body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">          
          <div class="span12"> 
	    <a class="brand" href="#">PANTHEON</a> 
          </div><!--/.span -->
        </div><!--/.container -->
      </div><!--/.navbar-inner -->
    </div><!--/.navbar -->

    <div class="container"> 
        <div class="span12">
          <div class="hero-unit">
            <h1 class="centered">Pantheon DNS Verification</h1>
            <h2 class="centered"><small>Before you go Live, check to make sure your PANTHEON DNS settings are correct</small></h2>
            <br />
	    <form  class="well form-search form-horizontal centered" method="GET">
              <h2 class="span1"> <small>https://</small></h2>
              <input type="text" name="hostname" id="hostname" placeholder="<?php print $site_url; ?>" size="90" class="span7 btn-large">
	      <button type="submit" class="btn btn-primary btn-large ">Check Domain»</button>
	    </form> 
	    <?php if($status == "pass" && !empty($hostname)): ?>
	    <div class="success"> 
	      <div class="alert alert-success"> 
		<h4><strong>Well done!</strong> Your domain is correctly configured!</h4>
	      </div> 
              <div class="page-header">
                <h2>Congratulations <small>It looks like your domain is correctly configured!</small></h2>
            </div>
	    </div>
	    <?php elseif($status == "fail" && !empty($hostname)): ?>
	    <div class="failure"> 
	      <div class="alert alert-error"> 
		<h4><strong>Oh no!</strong> There was an error in your domains configuration!</h4>
	      </div>
              <div class="page-header">
                <h2>OOps! <small>It looks something went wrong!</small></h2>
                <p>Before you go live you have to add select a plan. We have a guide that can take you the process of <a href="helpdesk.getpantheon.com/customer/portal/articles/361250-going-live#paying-for-Pantheon" title="Paying for Pantheon">paying for Pantheon</a>.
                </p>
                <p>If you have already setup your plan then make sure your <a href="http://helpdesk.getpantheon.com/customer/portal/articles/361250-going-live#setting-up-a-domain-with-Pantheon" title="Setting up a domain onPantheon">domain and DNS settings</a> on Pantheon are correct.
                </p> 								
                <?php if($status == "fail" && (sizeof($config_errors["A"]) < 1 || !$config_errors["A"]["match"])): ?> 
                  <h3>No valid DNS records for domain: <small><?php print $hostname; ?> </small></h3>    
                  <br />
                  <table class="table table-striped table-bordered table-condensed">
                    <thead>
                      <tr>
                        <th>Host</th>
		        <th>Target</th>
		        <th>Type</th>
                      </tr>
                   </thead>
                   <tbody>
                   <?php foreach($config_errors["A"]["list"] as $a_record): ?>
                     <tr>
                       <td>
		         <?php print ($a_record["host"]); ?> 
                       </td>
                       <td>
                          <?php print ($a_record["ip"]); ?> 
                       </td>
                       <td>
                          A
                       </td>
                     </tr> 
		   <?php endforeach; ?> 
                   <?php foreach($config_errors["CNAME"]["list"] as $a_record): ?>
                     <tr>
                       <td>
		         <?php print ($a_record["host"]); ?> 
                       </td>
                       <td>
                         <?php print ($a_record["ip"]); ?> 
                       </td>
                       <td>
                         CNAME
                       </td>
                     </tr> 
		   <?php endforeach; ?>
                   </tbody><!--/tbody-->
                 </table><!--/table-->
               <?php endif; ?>											               
              </div>
 	    </div> 
            <?php endif; ?>
       </div><!--/span-->
      </div><!--/row-->
      
      <hr>

      <footer>
        <div class="span12"> 
          <div class="row">
            <div class="span9">
              <p>
                <a href="https://www.getpantheon.com/" title="Pantheon | Zap! Instant Drupal">Home</a><small> &nbsp; | &nbsp; </small>
                <a href="https://www.getpantheon.com/about" title="About Us | Pantheon">About</a> <small>&nbsp; | &nbsp; </small>
                <a href="http://status.getpantheon.com/" title="Pantheon Platform Status">Status</a> <small>&nbsp; | &nbsp; </small>
                <a href="https://www.getpantheon.com/news" title="Code is Gold: Inside Pantheon | Pantheon">blog</a> <small>&nbsp; | &nbsp; </small>
                <a href="https://github.com/pantheon-systems/" title="Check us out on Github">github</a> <small>&nbsp; | &nbsp; </small>
                <a href="http://twitter.com/pantheon_drupal" title="Follow us on Twitter">@pantheon_drupal</a> <small>&nbsp; | &nbsp; </small>    
                <a href="https://www.getpantheon.com/contact" title="Get in touch | Pantheon">Contact</a>
             </p> 
           </div><!--/.span-->
           <div class="span3">    
             <p class="pull-right">&copy; PANTHEON Systems 2012</p>
           </div><!--/.span-->
         </div><!--/.row-->        
	</div><!--/.span-->
      </footer>

    </div><!--/.container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="docs/assets/js/jquery.js"></script>
    <script src="docs/assets/js/bootstrap-transition.js"></script>
    <script src="docs/assets/js/bootstrap-alert.js"></script>
    <script src="docs/assets/js/bootstrap-modal.js"></script>
    <script src="docs/assets/js/bootstrap-dropdown.js"></script>
    <script src="docs/assets/js/bootstrap-scrollspy.js"></script>
    <script src="docs/assets/js/bootstrap-tab.js"></script>
    <script src="docs/assets/js/bootstrap-tooltip.js"></script>
    <script src="docs/assets/js/bootstrap-popover.js"></script>
    <script src="docs/assets/js/bootstrap-button.js"></script>
    <script src="docs/assets/js/bootstrap-collapse.js"></script>
    <script src="docs/assets/js/bootstrap-carousel.js"></script>
    <script src="docs/assets/js/bootstrap-typeahead.js"></script>

  </body>
</html>
 