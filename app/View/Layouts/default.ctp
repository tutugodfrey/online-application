<!DOCTYPE html>
<html>
  <head>

    <?php echo $this->Html->charset(); ?>

    <title>
        <?php echo __('Axia - '); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <link href="/favicon.ico" type="image/x-icon" rel="icon" >
    <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" >

    <link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/uiContols.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

    <?php
        echo $this->Html->css('sessionMsg');
        if (array_key_exists('admin', $this->request->params) &&
            $this->request->params['admin'] === true) {
           echo $this->Html->css('cake.generic');
            ?>
            <style type="text/css">
                div#content div.index table {
                    font-size: 11px;
                }
            </style>
            <?php
        }
        else echo $this->Html->css('master.css');
    ?>

    <?php
        echo $scripts_for_layout;

		if ($this->Session->read('Auth.User.id')!=''){
            echo "<script type='text/javascript'>
                var c=0;
                var t;
                var timer_is_on=0;
				var remainingMins;

                function sessionCount(){
				//Substract 15 minutes to compensate for potential timeout de-synchronization with the server-side
                remainingMins = Math.floor((" . Security::inactiveMins() * Configure::read('Session.timeout') . " - c) / 60) - 15;
                document.getElementById('sessCountDn').innerHTML= remainingMins + ' minutes';
                /*If half the total session timeout is reached*/
                    if(remainingMins <= 5){
                        document.getElementById('sessCountDn').innerHTML= '<b>' + remainingMins + ((remainingMins > 1) ? ' minutes.</b>' : ' minute.</b>');
                        document.getElementById('msg_fader').style.display = 'block';
                        document.getElementById('session_box').style.display = 'block';
                       }
                     if(remainingMins <= 0){
                     this.t=null; //stops the t timeout Global Variable
                     window.location='/users/logout';
                     return
                    }
                c=c+1;
				t=setTimeout(\"sessionCount()\",990);//count down every 990 ms instead of 1000ms to compensate for potential client processor lag
                }

                function doTimer(){
					if (!timer_is_on){
						 timer_is_on=1;
						sessionCount();
					}
                }

				function resetTimer(){
					if(remainingMins < 10){
							jQuery.ajax({
								type: 'POST',
								url: '/Pages/refreshSession',
								dataType: 'html',
								success: function(data) {
									window.clearTimeout(t);
									c = 0;
									timer_is_on=0;
									doTimer();
									hideExpirationNotice();
								}
							});
					}
				}

				function hideExpirationNotice(){
					var fader = document.getElementById('msg_fader');
					var session_box = document.getElementById('session_box');
					fader.style.display = 'none';
					session_box.style.display = 'none';
				}
                window.onload=function() {
					          jQuery('body').on('click', resetTimer).on('keypress', resetTimer);
                    doTimer();
					}
        </script>";
         }
    ?>
</head>
<body>
	<div id='countTST'></div>
   <!-- Session notification Dialog Box -->
   <div id="msg_fader">&nbsp;</div>
  <div id="session_box" class="col-md-12">
    <div class="panel panel-danger">
      <div class="panel-heading text-center"><strong>Session Expiring Soon!</strong></div>
      <div class="panel-body">
         Session will expire in <span id="sessCountDn">&nbsp;</span>
         <br/>Unsaved work will be lost.
         <br/><span style="font-size: 10pt"><strong>Click or hit any key continue.</strong></span>
      </div> 
    </div>
  </div>

    <!-- End Session notification Dialog Box -->
    <!--  span id="loginBtn" class="btn">Need to login?</span -->
    <div id="container">
        <div id="header">
            <?php if (array_key_exists('admin', $this->request->params) &&
                      $this->request->params['admin'] === true): ?>
              <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <p class="navbar-text">
                <?php echo $this->Html->getCrumbs(' > ', array('text' => _('Axia Admin Home'), 'url' => '/admin/')); ?>
                </p>
                <p class="navbar-text navbar-right btn-group">
                <?php
			echo $this->Html->link(__('Applications'),
				array(
					'controller' => 'cobrandedApplications',
					'action' => 'index',
					'admin' => true,
				),
				array(
					'class' => 'btn btn-default'
				)
			);
			echo $this->Html->link(__('Coversheets'),
				array(
					'controller' => 'coversheets',
					'action' => 'index',
					'admin' => true,
				),
				array(
					'class' => 'btn btn-default'
				)
			);
			echo $this->Html->link(__('Logout'),
				array(
					'controller' => 'users',
					'action' => 'logout',
					'admin' => false,
				),
				array(
					'class' => 'btn btn-default'
				)
			);
		?>
                </p>
              </nav>
            <?php else: ?>
                <div style="margin-top: 25px;">
                <?php
                  if (!empty($brand_logo_url) || !empty($cobrand_logo_url)) {

                    $brand_logo = $this->Html->image($brand_logo_url, array('class' => 'pull-right'));
                    $cobrand_logo = $this->Html->image($cobrand_logo_url, array('height' => '50px'));

                    if (strlen($cobrand_logo_url) == 0) {
                      // no logo specified... use brand logo on the left
                      echo String::insert(
                        '<div class="row">' .
                        '<div class="col-md-12">:brand_logo</div>' .
                        '</div>',
                        array(
                          'brand_logo' => $brand_logo
                        )
                      );
                    } elseif ($include_brand_logo == true) {
                      // only one way to display this combination
                      if ($cobrand_logo_position < 3) {
                        echo String::insert(
                          '<div class="row">' .
                          '<div class="col-md-6">:cobrand_logo</div>' .
                          '<div class="col-md-6">:brand_logo</div>' .
                          '</div>',
                          array(
                            'cobrand_logo' => $cobrand_logo,
                            'brand_logo' => $brand_logo
                          )
                        );
                      }
                      else {
                        echo String::insert(
                          '<div class="row">' .
                          '<div class="col-md-6">:brand_logo</div>' .
                          '</div>',
                          array(
                            'brand_logo' => $brand_logo
                          )
                        );
                      }
                    } else {
                      // position the logo left,center or right
                      $logo_position = $cobrand_logo_position;
                      if ($logo_position < 3) {
                        echo String::insert(
                          '<div class="row">' .
                          '<div class="col-md-12 text-:position">:cobrand_logo</div>' .
                          '</div>',
                          array(
                            'cobrand_logo' => $cobrand_logo,
                            'position' => $logoPositionTypes[$logo_position]
                          )
                        );
                      }
                    }
                  }
                  else {
                    echo $this->Html->image(
                      'logo.png',
                      array(
                        'alt'=> __('Axia'),
                        'border' => '0',
                        'style' => 'display: block; margin-left:auto; margin-right: auto;'
                      )
                    );
                  }
                ?>
              </div>
            <?php endif; ?>
        </div>
        <div id="content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $this->fetch('content'); ?>
        </div>
        <div id="footer">
            <?php /* echo $this->Html->link(
                    $this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework'), 'border' => '0')),
                    'http://www.cakephp.org/',
                    array('target' => '_blank', 'escape' => false)
                ); */
            ?>
        </div>
    </div>
</body>
</html>
