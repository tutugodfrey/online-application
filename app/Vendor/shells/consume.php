<?php
App::import('Core', 'HttpSocket');
class ConsumeShell extends Shell {
    protected static $baseUrl;
    protected static $httpSocket;
    //protected static $token;
    public function main() {
        //OLD CODE
         if (empty($this->args) || count($this->args) !=1) {
            $this->err('USAGE: cake consume <baseUrl>');
            $this->_stop();
        }
        self::$baseUrl = $this->args[0];
        
        $this->test();
          
        //NEW CODE
        /*
        if (empty($this->args) || count($this->args) != 2) {
            $this->err('USAGE: cake consume <baseUrl> <token>');
            $this->_stop();
        }
        list(self::$baseUrl, self::$token) = $this->args;
        $this->test();      
         * 
         */
        
    }
    
    protected function test() {
        $this->request('/api/epayments/add.json', 'POST', array(
            //'user_id' => '12',
            'pin' => '3451',
            'application_id' => '1241',
            'merchant_id' => '7641110042403565',
            'user_id' => '14'
        ));
        /*
        $lastId = $this->listApps();
        $this->hr();
        /*
        $this->request('/api/applications/edit/'.$lastId.'.json', 'POST', array(
            'user_id' => '12',
            'corporate_email' => 'stanner@axiapayments.com',
            'legal_business_name' => 'Seth',
            'mailinig_address' => '1216 State Street',
            'mailing_city' => 'Santa Barbara',
            'mailing_state' => 'CA',
            'mailing_zip' => '93101'
        ));
        $this->listApps();
        $this->hr();
        $this->request('/api/applications/delete/'.$lastId.'.json', 'DELETE');
        $this->listApps();
         * 
         */
    }
    
    protected function request($url, $method='GET', $data=null) {
        if (!isset (self::$httpSocket)) {
            self::$httpSocket = new HttpSocket();
        } else {
            self::$httpSocket->reset();
        }
        $body = self::$httpSocket->request(array(
            'method' => $method,
            'uri' => self::$baseUrl . '/' . $url,
            'body' => $data /*,
            'auth' => array(//This line added by omm
                'user' => self::$token,
                'pass' => ''
            )*/
        ));
        if ($body === false || self::$httpSocket->response['status']['code'] != 200) {
            $error = 'ERROR while performing ' .$method.' to '.$url;
            if ($body !== FALSE) {
                $error = '[' . self::$httpSocket->response['status']['code'] . ']' . $error;
            }
            $this->err($error);
            $this->_stop();
        }
        return $body;
    }
    
    protected function listApps() {
        $response = json_decode($this->request('api/epayments.json'));
        $lastId = null;
        foreach($response as $item) {
            $lastId = $item->Epayment->id;
            $this->out($item->Epayment->id . ': ' . $item->Epayment->merchant_id);
        }
        return $lastId;
    }
}
?>
