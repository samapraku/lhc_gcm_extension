

# lhc_gcm_extension
An extension to enable FCM push notifications for livehelperchat android app.

**Current version: 0.1.3**  
**Change Log:**  
Added Support for twilio SMS  
Push notification bug fixes  

**For UPDATE**  

If you already have the extension installed,
1. Just replace the content of <LHC_install_dir>/extension/gcm with the content from 'gcm' directory you have downloaded.
2. Clear LHC cache from LHC settings page for update to reflect.
3. In the app, Go to "Server Details" in the menu Drawer and tap Re-Sync in the top bar.

**Installation**  

To Install Extension 
1. Download the extension and copy the 'gcm' directory to <LHC_install_dir>/extension
2. Activate the extension by adding 'gcm' to the 'extension' array in <LHC_install_dir>/settings/settings.ini.php 
[e.g look for  'settings' => array ('site' =>array ( 'extensions' =>array ( 0 =>'gcm'),]

Refer to this for example: https://github.com/LiveHelperChat/livehelperchat/blob/0f5127f58d0238888d6dc368662cfcd6fcc62c7e/lhc_web/settings/settings.ini.default.php#L25

Note: Path to extension directory should be '<LHC_install_dir>/extension/gcm' else it won't work.

**CONFIGURE APP**

3. Login/Password for app is same as the web logins
4. Server url is the address to your livehelperchat installation. Preferrably, use a subdomain.
    eg. http://livehelperchat.yourdomain.com or https://yourdomain.com/livehelperchat

5. If the registration is successful on your server, you will receive a notification in the notification bar of your phone telling you 'Device Successfully Registered'.  
6.  You will receive new push notifications for new chat request, new messages and unread messages.

If you do not receive a push notification after login, then try the next step.

7. In the app, open the menu drawer, select Server Details and Tap 'Sync Server'.

**Issues**  

The twilio extension requires the REST API. However, the rest API might not work with the default .htaccess configuration.
Adding the configuration below to .htaccess file may work. 

```
Header set Access-Control-Allow-Credentials true
Header always Set Access-Control-Allow-Methods: "GET, POST, OPTIONS, PUT, DELETE"
Header always Set Access-Control-Allow-Headers: "Origin, X-Requested-With, Content-Type, Accept, API-Key, Authorization" 


SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
```

