<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    
    <title>
        <?php echo __('Axia - '); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <link href="/favicon.ico" type="image/x-icon" rel="icon" ><link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" >
    <?php
//        echo $this->Html->meta('icon');
        echo $this->Html->css('sessionMsg');
        if ($this->request->params['admin'] === true) {
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
    <link rel="stylesheet" type="text/css" href="http<?php echo (env('HTTPS') ? 's' : ''); ?>://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"></link>
    <script type="text/javascript" src="http<?php echo (env('HTTPS') ? 's' : ''); ?>://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script> 
    <script type="text/javascript" src="http<?php echo (env('HTTPS') ? 's' : ''); ?>://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script> 
    <script type="text/javascript" src="/js/uiContols.js"></script>
        
    <?php
        
        echo $scripts_for_layout;       
        
         if ($this->Session->read('Auth.User.id')!=''){
             //echo Security::inactiveMins() * Configure::read('Session.timeout');
            echo "<script type='text/javascript'>                
                var c=0;
                var t;
                var timer_is_on=0;
                
               

                function sessionCount(){
                var remainingMins = Math.round((" . Security::inactiveMins() * Configure::read('Session.timeout') . " - c) / 60) ;
                document.getElementById('sessCountDn').innerHTML= remainingMins + ' minutes';
                /*If half the total session timeout is reached*/
                    if(remainingMins == 10){                        
                        var today=new Date();
                        var h=today.getHours();
                        var m=(today.getMinutes() < 10) ? '0' + today.getMinutes() : today.getMinutes();
                        var amPM=(h < 12) ? 'am' : 'pm';
                        document.getElementById('currentTime').innerHTML= h + ':' + m + amPM;
                    }
                    if(remainingMins <= 10){
                        document.getElementById('sessCountDn').innerHTML= remainingMins + ' minutes from: ';                        
                        document.getElementById('msg_fader').style.display = 'block';
                        document.getElementById('session_box').style.display = 'block';
                       }
                     if(remainingMins == 0){                      
                     window.location='https://app.axiapayments.com/users/logout';                     
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
                  //var login_btn = document.getElementById('loginBtn');
                    var fader = document.getElementById('msg_fader');
                    var session_box = document.getElementById('session_box');
                    var cancel = document.getElementById('cancelBtn');
                    //Display login box
                  //  login_btn.onclick=function() {
                  //     fader.style.display = 'block';
                   //    session_box.style.display = 'block';}
                    //Hide Login Box
                    cancel.onclick = function() {
                            var isAppStep = 0;
                            var pElements = document.getElementsByTagName('p');
                            var regExPattrn=/steps/gi;
                            fader.style.display = 'none';
                            session_box.style.display = 'none';
                            
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
      <p style="color:black ">Session will expire in <span id="sessCountDn">$nbsp;</span><span id="currentTime">$nbsp;</span>
           <br />If session expires you must to log back in and re-enter any unsaved work that was lost upon session expiration.
        
           </p> 
           
       <span id="cancelBtn" class="btn">Continue</span>
           
   </div>
    <!-- End Session notification Dialog Box --> 
<!--  span id="loginBtn" class="btn">Need to login?</span -->

    <div id="container">
        <div id="header">
            <?php if ($this->request->params['admin'] === true): ?>
                <h1><?php echo $this->Html->link(__('Axia Admin'), '/admin/'); ?></h1>
                <div style="float: right; margin-top: -25px;"><?php echo $this->Html->link(__('Logout'), '/users/logout', array('style' => 'color: #fff;')); ?></div>
            <?php else: ?>
                <div style="margin-top: 25px;">
                <?php echo $this->Html->image('logo.png', array('alt'=> __('Axia'), 'border' => '0', 'url' => 'http://www.axiapayments.com')); ?>
               <div style="float:right; margin-right: 10%; margin-top: 2%;"> <?php echo $this->Html->image('logo-header.png', array('alt'=> __('PaymentSpring'), 'border' => '0', 'url' => 'http://www.firespring.org')); ?></div>

                    </div>
                
            <?php endif; ?>
        </div>
        <div id="content">

            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>

            <?php echo $this->fetch('content'); ?>

        </div>
        <div id="footer">
        </div>
    </div>
</body>
</html>
