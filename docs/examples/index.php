<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PANTHEON DNS Verification</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        background: url("../assets/img/grid-18px-masked.png") repeat scroll 0 0 transparent;
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .hero-unit .centered{
        text-align: center;
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

 <?php
   $hostname = $_GET['hostname']; 
$status = "none";
if(!empty($hostname) && (sizeof($hostname) > 0)){
  $result = dns_get_record($hostname, DNS_ANY);
  //  echo "Result = ";
  //  print_r($result); 
  
  foreach( $result as $d ) {
    // Only print A and MX records
    if( $d['type'] != "A" and $d['type'] != "MX" )
      continue;
    // First print all fields
    echo "--- " . $d['host'] . ": <br />\n";
    foreach( $d as $key => $value ) {
      if( $key != "host" )    // Don't print host twice
	echo " {$key}: {$value} <br />\n";
    }
    // Print type specific fields
    switch( $d['type'] ) {
    case 'A':
      // Display annoying message
      echo "A records always contain an IP address. <br />\n";
      break;
    case 'MX':
      // Resolve IP address of the mail server
      $mx = dns_get_record( $d['target'], DNS_A );
      foreach( $mx as $server ) {
	echo "The MX record for " . $d['host'] . " points to the server " . $d['target'] . " whose IP address is " . $server['ip'] . ". <br />\n";
      }
      break;
    }
  }
  
}

elseif(!empty($hostname) && (sizeof($hostname) <= 0)){
  
  $status = "fail";
}
 
?>
  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">          
          <div class="span12"> 
	    <a class="brand" href="#">PANTHEON</a> 
          </div><!--/.span -->
        </div>
      </div>
    </div>

    <div class="container"> 
        <div class="span12">
          <div class="hero-unit">
            <h1 class="centered">Pantheon DNS Verification</h1>
            <h2 class="centered"><small>Before you go Live, check to make sure your PANTHEON DNS settings are correct</small></h2>
            <br />
	    <form  class="well form-search form-horizontal centered" method="GET">
              <label> https://</label><input type="text" name="hostname" placeholder="dnscheck.gotpantheon.com" size="90" class="span6 btn-large" />
	      <button class="btn btn-primary btn-large" type="submit">Check Domain&raquo;</button>
	    </form> 
            <?php if($status == "pass"): ?>
	    <div class="success"> 
	      <div class="alert alert-success">
		<a class="close" data-dismiss="alert">×</a>
		<strong>Well done!</strong> Your domain is correctly configured!
	      </div> 
	    </div>
            <?php elseif($status == "fail"): ?>
	    <div class="failure"> 
	      <div class="alert alert-error">
		<a class="close" data-dismiss="alert">×</a>
		<strong>Oh snap!</strong> There was an error in your domains configuration!.
	      </div>
	    </div> 
            <?php endif; ?>
            <?php if(!empty($hostname)): ?>
	    <table class="table table-bordered table-striped">
              <colgroup>
		<col class="span1">
		<col class="span7">
              </colgroup>
              <thead>
		<tr>
		  <th>Tag</th>
		  <th>Description</th>
		</tr>
              </thead>
              <tbody>
		<tr>
		  <td>
		    <code>&lt;URL&gt;</code>
		  </td>
		  <td>
		    http://dnscheck.gotpantheon.com
		  </td>
		</tr>
		<tr>
		  <td>
		    <code>&lt;I.P Address&gt;</code>
		  </td>
		  <td>
		    127.1.1.0
		  </td>
		</tr>
		<tr>
		  <td>
		    <code>&lt;CNAME&gt;</code>
		  </td>
		  <td>
		    Container element for table header rows (<code>&lt;tr&gt;</code>) to label table columns
		  </td>
		</tr>
		<tr>
		  <td>
		    <code>&lt;Domain Name&gt;</code>
		  </td>
		  <td>
		    Container element for table rows (<code>&lt;tr&gt;</code>) in the body of the table
		  </td>
		</tr>
		<tr>
		  <td>
		    <code>&lt;tr&gt;</code>
		  </td>
		  <td>
		    Container element for a set of table cells (<code>&lt;td&gt;</code> or <code>&lt;th&gt;</code>) that appears on a single row
		  </td>
		</tr>
		<tr>
		  <td>
		    <code>&lt;td&gt;</code>
		  </td>
		  <td>
		    Default table cell
		  </td>
		</tr>
		<tr>
		  <td>
              <code>&lt;th&gt;</code>
		  </td>
		  <td>
		    Special table cell for column (or row, depending on scope and placement) labels<br>
		    Must be used within a <code>&lt;thead&gt;</code>
		  </td>
          </tr>
		<tr>
		  <td>
		    <code>&lt;caption&gt;</code>
		  </td>
		  <td>
		    Description or summary of what the table holds, especially useful for screen readers
		  </td>
		</tr>
              </tbody>
	    </table> <!--/table-->
	    <?php endif; ?> <!--/endif hostname-->
        </div><!--/span-->
      </div><!--/row-->
      
      <hr>

      <footer>
        <p>&copy; PANTHEON Systems 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>

  </body>
</html>
