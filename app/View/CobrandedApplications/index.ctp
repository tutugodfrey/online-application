<?php
    if (!$this->Session->check('Auth.User.id')) {
        echo $this->Element('Ajax/dynamicModal');
    }
    $incomplete = $waiting = $completed = 0;
    $allowSigning = false;
    $expriedLabel = "<span class='label label-default nowrap'>Expired</span>";
    $tokenExpDate = date('l M jS',strtotime($appGroupData['ApplicationGroup']['client_pw_expiration']));

    $datetime1 = date_create(date('Y-m-d H:i:s'));
    $datetime2 = date_create($appGroupData['ApplicationGroup']['client_pw_expiration']);

    // Calculates the difference between DateTime objects
    $interval = date_diff($datetime1, $datetime2);
      
    // Display the result
    $remainingD = (int)$interval->format('%a');
    $remainderConcat = '';
    $remainderConcat .= ($remainingD>0)?"in $remainingD day":'today';
    $remainderConcat .= ($remainingD>1)?"s ":' ';
    $remainderConcat = ($remainingD <=8)? $remainderConcat : $remainderConcat ."on ". $tokenExpDate;
?>
<div style="width:80%" class="center-block">
    <div class="panel panel-primary">
        <div class="panel-heading text-center">Client Documents Dashboard</div>
        <div class="panel-body">
            <h3 class="text-center text-primary">Hello and Welcome!</h3>
            <div class="panel panel-default small">
                <span class="glyphicon glyphicon-info-sign text-info pull-left" style="font-size:20pt;margin-top:2.7%"></span>
                <div class="panel-heading text-center">
                    <p class="text-left" style="margin-left:40px">This dashboard provides an overview of all your forms, applications and documents that have been saved, are awaiting signature, and/or are signed and completed.<br/>
                    The <u>"Incomplete Applications"</u> section contains applications that are in the initial stage, and are yet to be completely filled out.<br/>
                    Once an application is completely filled out and signature is required it will be listed in the <u>"Applications Waiting for Signature"</u> section (a signature may not always be required depending on the type of application).
                    <br/>Use the search form below to find your applications and/or documents if they are not already listed. If you have any questions about your applications, please feel free to contact your sales representative.</p>
                    <?php
                        if ((int)$datetime1->format('U') > (int)$datetime2->format('U')) {
                            echo  '<strong class="text-danger bg-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> Client access to this page has expired, only logged in users may currently have access.</strong><br/>';
                        } else {
                            echo "<strong>For security, access to this page will expire <span class='text-danger'>$remainderConcat</span>.";
                        }
                    ?>
                   
                        <?php if (!empty($this->Session->read('Auth.User.id')) || ($remainingD <= 8 && $appGroupData['ApplicationGroup']['token_renew_count'] <= 2)) { 
                            echo "If more time is needed to complete your documents, you may extend this expiration: ";
                            echo $this->Form->postLink('Extend', array('admin' => false, 'controller' => 'CobrandedApplications', 'action' => 'extend_dashboard_expiration', $appGroupData['ApplicationGroup']['id']), array('class' => 'btn btn-default btn-xs', 'confirm' => "An email notification will be sent to the client containing renewed access credentials.\nContinue?"));
                        }
                        if (empty($this->Session->read('Auth.User.id')) && $appGroupData['ApplicationGroup']['token_renew_count'] > 2) {
                            echo "<br/>If you need to extend this expiration, please contact you sales representative.";
                        }
                        ?>
                    </strong>


            </div>
            <?php
                echo $this->Session->flash();
                echo $this->Form->create(array(
                        'inputDefaults' => array(
                            'div' => 'form-group',
                            'label' => array('class' => 'control-label'),
                            'wrapInput' => 'col-md-12 col-sm-12',
                            'div' => 'col-md-4 col-sm-5',
                            'class' => 'col-md-12 col-sm-12',
                        ),
                        'type' => 'post',
                        'class' => 'row form-inline well-sm'
                    ));

                echo $this->Form->input('CobrandedApplication.email_value', array('div' => 'col-md-3 col-sm-5', 'wrapInput' => 'col-md-12 col-sm-12', 'autocomplete' => 'off','type' => 'email', 'label' => 'Search by Owner/Officer Email:<br><span class="text-muted small nowrap">(must match the email entered in your application(s))</span>'));
                echo $this->Form->input('CobrandedApplication.doc_id', array('autocomplete' => 'off','type' => 'text', 'label' => 'Or search by document id:<br><span class="text-muted small nowrap">(example: d5f4795c-d501-4411-b849-f48a9d60eed6)</span>'));
                echo $this->Form->submit(__('Search'), array('div' => false, 'class' => 'btn btn-sm btn-primary', 'style'=> 'margin-top:14px'));
                echo $this->Form->end();
            ?>
        </div>
        <div class="list-group ">
            <div class="list-group-item panel-primary clearfix">
                <h4 class="text-primary text-center">Incomplete Applications:</h4>
                <?php
                    $responsiveCount = 0;
                    $noAppNameHtml = '<span class="text-muted text-center center-block small">(Application has no name yet)</span>';
                    foreach ($applications as $key => $val) {
                        if (in_array($val['CobrandedApplication']['status'], array('saved', 'validate', 'pending'))) {
                            
                            if ($val['ValuesMap']['DBA']) $name = $val['ValuesMap']['DBA'];
                            elseif ($val['ValuesMap']['CorpName']) $name = $val['ValuesMap']['CorpName'];
                            else $name = $noAppNameHtml;

                            $incomplete = 1;
                            $isExpired = !CakeTime::wasWithinLast(Configure::read('App.access_validity_age'), $val['CobrandedApplication']['modified']);
                            $statusLabel = "<span class='label label-warning nowrap'>Pending</span>";
                            echo $this->Element('cobranded_applications/index_doc_thumbnail', 
                                [
                                    'uuid' => $val['CobrandedApplication']['uuid'],
                                    'cobrandName' => $val['Cobrand']['partner_name'],
                                    'name' => $name,
                                    'thumbImg' => ($isExpired)? "/img/doc_generic_gray.png":"/img/doc_generic.png",
                                    'isExpired' => $isExpired,
                                    'workflowComplete' => false,
                                    'statusLabel' => ($isExpired)? $expriedLabel : $statusLabel,
                                    'cobrandedApplication' => $val['CobrandedApplication'],
                                    'hasCoversheet' => isset($val['Coversheet']['id']),
                                    'allowSigning' => false
                                ]);
                            $responsiveCount+=1;
                            if (($responsiveCount % 4) === 0) {
                                echo '<div class="clearfix visible-lg-block"></div>';
                            } 
                            if (($responsiveCount % 3) === 0) {
                                echo '<div class="clearfix visible-md-block"></div>';
                            }
                            unset($applications[$key]);
                        }
                    }
                    
                    if (!$incomplete) echo '<div class="text-center text-muted small"><i>(None found)</i></div>';
                ?>
                
            </div>
            <div class="list-group-item panel-primary clearfix">
                <h4 class="text-primary text-center">Applications Waiting for Signature:</h4>
                <?php
                    $responsiveCount = 0;
                    foreach ($applications as $key => $val) {
                        //Check if merchant is even allowed to sign
                        $allowSigning = ($val['CobrandedApplication']['status'] == 'completed' && 
                                        filter_var(Hash::get($val, 'ValuesMap.AllowMerchantToSignApplication'), FILTER_VALIDATE_BOOLEAN) === true &&
                                        !empty($val['CobrandedApplication']['rightsignature_document_guid']));
                        if ($allowSigning) {
                            if ($val['ValuesMap']['DBA']) $name = $val['ValuesMap']['DBA'];
                            elseif ($val['ValuesMap']['CorpName']) $name = $val['ValuesMap']['CorpName'];
                            else $name = $noAppNameHtml;
                            $waiting = 1;

                            $statusLabel = "<span class='label label-info nowrap'>Pending Signatures</span>";
                            echo $this->Element('cobranded_applications/index_doc_thumbnail', 
                                [
                                    'uuid' => $val['CobrandedApplication']['uuid'],
                                    'cobrandName' => $val['Cobrand']['partner_name'],
                                    'name' => $name,
                                    'thumbImg' => "/img/doc_generic.png",
                                    'isExpired' => false,
                                    'workflowComplete' => false,
                                    'statusLabel' => $statusLabel,
                                    'cobrandedApplication' => $val['CobrandedApplication'],
                                    'hasCoversheet' => isset($val['Coversheet']['id']),
                                    'allowSigning' => $allowSigning
                                ]);
                            $responsiveCount+=1;
                            if (($responsiveCount % 4) === 0) {
                                echo '<div class="clearfix visible-lg-block"></div>';
                            } 
                            if (($responsiveCount % 3) === 0) {
                                echo '<div class="clearfix visible-md-block"></div>';
                            }
                            unset($applications[$key]);
                        }
                    }
                    $allowSigning = false;

                    if (!$waiting) echo '<div class="text-center text-muted small"><i>(None found)</i></div>';
                ?>
            </div>
            <div class="list-group-item panel-primary clearfix">
                <h4 class="text-primary text-center">Signed and Completed Applications:</h4>
                <?php
                     $responsiveCount = 0;
                    foreach ($applications as $key => $val) {
                        if ($val['CobrandedApplication']['status'] == 'signed' ||
                        //completed applications that clients are not allowed to signed are considered done
                        ($val['CobrandedApplication']['status'] == 'completed' && filter_var(Hash::get($val, 'ValuesMap.AllowMerchantToSignApplication'), FILTER_VALIDATE_BOOLEAN) === false )) {
                            if ($val['ValuesMap']['DBA']) $name = $val['ValuesMap']['DBA'];
                            elseif ($val['ValuesMap']['CorpName']) $name = $val['ValuesMap']['CorpName'];
                            else $name = $noAppNameHtml;
                            
                            $statusLabel = "<span class='label label-success nowrap'>" .ucfirst($val['CobrandedApplication']['status']). "</span>";
                            echo $this->Element('cobranded_applications/index_doc_thumbnail', 
                                [
                                    'uuid' => $val['CobrandedApplication']['uuid'],
                                    'cobrandName' => $val['Cobrand']['partner_name'],
                                    'name' => $name,
                                    'thumbImg' => "/img/doc_generic_check.png",
                                    'isExpired' => false,
                                    'workflowComplete' => true,
                                    'statusLabel' =>  $statusLabel,
                                    'cobrandedApplication' => $val['CobrandedApplication'],
                                    'hasCoversheet' => isset($val['Coversheet']['id']),
                                    'allowSigning' => false
                                ]);
                            $responsiveCount+=1;
                            if (($responsiveCount % 4) === 0) {
                                echo '<div class="clearfix visible-lg-block"></div>';
                            } 
                            if (($responsiveCount % 3) === 0) {
                                echo '<div class="clearfix visible-md-block"></div>';
                            }
                            unset($applications[$key]);
                            $completed = 1;
                        }
                    }
                    
                    if (!$completed) echo '<div class="text-center text-muted small"><i>(None found)</i></div>';
                ?>
            </div>
        </div>
    </div>
</div>