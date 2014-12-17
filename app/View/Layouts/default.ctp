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
             //echo Security::inactiveMins() * Configure::read('Session.timeout');
            echo "<script type='text/javascript'>
                var c=0;
                var t;
                var timer_is_on=0;

                function sessionCount(){
                var remainingMins = Math.round((" . Security::inactiveMins() * Configure::read('Session.timeout') . " - c) / 60 );
                document.getElementById('sessCountDn').innerHTML= remainingMins + ' minutes';
                /*If half the total session timeout is reached*/
                    
                    if(remainingMins <= 10){
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
                t=setTimeout(\"sessionCount()\",1000);
                }

                function doTimer(){
                if (!timer_is_on){
                  timer_is_on=1;
                  sessionCount();
                  }
                }
                //window.onload = doTimer;
                window.onload=function() {
                    doTimer();
                    var cancel = document.getElementById('cancelBtn');
                    //Hide Login Box
                    cancel.onclick = function() {
                            document.getElementById('refreshIMG').style.display='block';
                            var fader = document.getElementById('msg_fader');
                            var session_box = document.getElementById('session_box');
                            var isAppStep = 0;
                            var pElements = document.getElementsByTagName('p');
                            var regExPattrn=/steps/gi;

                            for(x=0; x < pElements.length;x++){
                                if(pElements[x].className.match(regExPattrn)){
                                    isAppStep = 1;
                                    break;
                                }
                            }
                            if(isAppStep)
                                document.forms[0].submit()
                            else
                                window.location.reload()
                            fader.style.display = 'none';
                            session_box.style.display = 'none';
                            session_box.removeChild(document.getElementById('cancelBtn'));
                         }
                     }
        </script>";
         }
    ?>
</head>
<body>
   <!-- Session notification Dialog Box --> 
   <div id="msg_fader">&nbsp;</div>
   <div id="session_box" >
       <h2>Session Expiring Soon</h2>
      <p style="color:black ">Session will expire in <span id="sessCountDn">$nbsp;</span>
           <br />If session expires you must to log back in and re-enter any unsaved work that was lost upon session expiration.
           </p> 
        <img src="/img/refreshing.gif" id="refreshIMG" style="display:none;float:right; margin-right: 25px" />   
       <span id="cancelBtn" class="btns">Continue</span>
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
            <?php endif; ?>

                <div style="margin-top: 100px;">
                <?php
                  if (!empty($cobrand_logo_url)) {
                    $partner_logo = $this->Html->image($cobrand_logo_url, array('height' => '50px'));
                    if (strlen($cobrand_logo_url) == 0) {
                      // no logo specified... use axia logo on the left
                      echo String::insert(
                        '<div class="row">' .
                        '<div class="col-md-12">:axia_logo</div>' .
                        '</div>',
                        array(
                          'axia_logo' => $this->Html->image('/img/axia_logo.png', array('class' => 'pull-right'))
                        )
                      );
                    } elseif ($include_axia_logo == true) {
                      // only one way to display this combination
                      if ($cobrand_logo_position < 3) {
                        echo String::insert(
                          '<div class="row">' .
                          '<div class="col-md-6">:partner_logo</div>' .
                          '<div class="col-md-6">:axia_logo</div>' .
                          '</div>',
                          array(
                            'partner_logo' => $partner_logo,
                            'axia_logo' => $this->Html->image('/img/axia_logo.png', array('class' => 'pull-right'))
                          )
                        );
                      }
                      else {
                        echo String::insert(
                          '<div class="row">' .
                          '<div class="col-md-6">:axia_logo</div>' .
                          '</div>',
                          array(
                            'axia_logo' => $this->Html->image('/img/axia_logo.png', array('class' => 'pull-right'))
                          )
                        );
                      }
                    } else {
                      // position the logo left,center or right
                      $logo_position = $cobrand_logo_position;
                      if ($logo_position < 3) {
                        echo String::insert(
                          '<div class="row">' .
                          '<div class="col-md-12 text-:position">:partner_logo</div>' .
                          '</div>',
                          array(
                            'partner_logo' => $partner_logo,
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
