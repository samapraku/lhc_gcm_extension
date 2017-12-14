# lhc_gcm_extension
An extension to enable FCM push notifications for livehelperchat android app.

To Install Extension

1. Download the extension and copy the 'gcm' directory to <LHC_install_dir>/extension
2. Activate the extension by adding 'gcm' to the 'extension' array in <LHC_install_dir>/settings/settings.ini.php 
[e.g look for  'settings' => array ('site' =>array ( 'extensions' =>array ( 0 =>'gcm'),]

Refer to this for example: https://github.com/LiveHelperChat/livehelperchat/blob/0f5127f58d0238888d6dc368662cfcd6fcc62c7e/lhc_web/settings/settings.ini.default.php#L25

Note: Path to extension should be '<LHC_install_dir>/extension/gcm' else it won't work.

ENABLE PUSH IN THE APP

3. After enabling the extension on your server from step 2 above, Go to the app menu -> Push Notifications 
4. Tap the 'Save FCM KEY TO YOUR SERVER' to register your device on your server.
5. If the registration is successful on your server, you will receive a notification in the notification bar of your fone telling you 'Device Successfully Registered'.
6.  You will receive new push notifications for new chat request, new messages and unread messages.
