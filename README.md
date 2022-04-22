Axia Online Merchant Application                                                                                           
                                                                                                                           
![Axia](http://www.axiapayments.com/wp-content/themes/axiapayments/images/axia-logo.svg)                                   
                                                                                                                           
The Axia Online Merchant Application, is a web based version of our standard                                               
merchant application.  It is built to be flexible and customizable with the                                                
ability to easily add "Cobrands" Partner Branded application "Templates."                                                  
The templates correspond to PDF signature templates that are integrated with                                               
[RightSignature](https://rightsignature.com).  This application also provides a                                            
basic [RestAPI] to allow to programmatically create new applications
                                                                                                                           
                                                                                                                           
Building the Application                                                                                                   
----------------                                                                                                           
                                                                                                                           
```                                                                                                                        
git clone git@github.com:PaymentFusion/online-application.git                                                             
cd online-application                                                                                                      
composer install && composer update
app/Console/cake Migrations.migration run all                                                                              
```                                                                                                                        
                                                                                                                           
At this point you should be up and running                                                                                 
                                                                                                                           
This Application is built using the [CakePHP](http://www.cakephp.org) rapid                                                
development framework.                                                                                                     
                                                                                                                           
Some Handy Links relating to the framework                                                                                 
----------------                                                                                                           
                                                                                                                           
[CakePHP](http://www.cakephp.org) - The rapid development PHP framework                                                    
                                                                                                                           
[Cookbook](http://book.cakephp.org) - THE Cake user documentation; start learning here!                                    
                                                                                                                           
[Plugins](http://plugins.cakephp.org/) - A repository of extensions to the framework                                       
                                                                                                                           
[The Bakery](http://bakery.cakephp.org) - Tips, tutorials and articles                                                     
                                                                                                                           
[API](http://api.cakephp.org) - A reference to Cake's classes                                                              
                                                                                                                           
[CakePHP TV](http://tv.cakephp.org) - Screen casts from events and video tutorials                                         
                                                                                                                           
[The Cake Software Foundation](http://cakefoundation.org/) - promoting development related to CakePHP                      
                                                                                                                           
Framework Support                                                                                                          
------------                                                                                                               
                                                                                                                           
[Our Google Group](http://groups.google.com/group/cake-php) - community mailing list and forum                             
                                                                                                                           
[#cakephp](http://webchat.freenode.net/?channels=#cakephp) on irc.freenode.net - Come chat with us, we have cake.          
                                                                                                                           
[Q & A](http://ask.cakephp.org/) - Ask questions here, all questions welcome
