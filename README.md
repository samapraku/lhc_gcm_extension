

# lhc_gcm_extension
An extension to enable FCM push notifications for livehelperchat android app.

**For UPDATE**
If you already have the extension installed,
1. Just replace the content of <LHC_install_dir>/extension/gcm with the content from 'gcm' directory.
2. Clear LHC cache from LHC settings page for update to reflect.

**Installation**
To Install Extension 
1. Download the extension and copy the 'gcm' directory to <LHC_install_dir>/extension
2. Activate the extension by adding 'gcm' to the 'extension' array in <LHC_install_dir>/settings/settings.ini.php 
[e.g look for  'settings' => array ('site' =>array ( 'extensions' =>array ( 0 =>'gcm'),]

Refer to this for example: https://github.com/LiveHelperChat/livehelperchat/blob/0f5127f58d0238888d6dc368662cfcd6fcc62c7e/lhc_web/settings/settings.ini.default.php#L25

Note: Path to extension directory should be '<LHC_install_dir>/extension/gcm' else it won't work.

**CONFIGURE APP**
3. Login/Password for app is same as the web logins
4. Server url is the address to your livehelperchat installation. 
    eg. http://livehelperchat.yourdomain.com or https://yourdomain.com/livehelperchat
    
**5. After Login, Open the menu drawer, select Server Info and Tap 'Sync Server'.**



THE STEPS BELOW ARE NO LONGER NECESSARY SINCE app version 1.2.0

~~ENABLE PUSH IN THE APP~~

~~5. After enabling the extension on your server from step 2 above, Go to the app menu -> Push Notifications 
6. Tap the 'Save FCM KEY TO YOUR SERVER' to register your device on your server.
7. If the registration is successful on your server, you will receive a notification in the notification bar of your phone telling you 'Device Successfully Registered'.
8.  You will receive new push notifications for new chat request, new messages and unread messages.~~
