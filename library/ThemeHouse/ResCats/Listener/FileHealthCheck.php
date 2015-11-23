<?php

class ThemeHouse_ResCats_Listener_FileHealthCheck
{

    public static function fileHealthCheck(XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes = array_merge($hashes,
            array(
                'library/ThemeHouse/ResCats/BbCode/Formatter/BbCode/Description.php' => 'a460d69ed5901c5ae030f6f6e690b9db',
                'library/ThemeHouse/ResCats/Extend/XenResource/ControllerHelper/Resource.php' => '60dea88ceb29b8d8106e97699975c812',
                'library/ThemeHouse/ResCats/Extend/XenResource/ControllerPublic/Resource.php' => '105ead5683dfd844e35102f206a9bfb7',
                'library/ThemeHouse/ResCats/Extend/XenResource/Model/Category.php' => 'e2c9ec39a88d6c20a8fc94519c815569',
                'library/ThemeHouse/ResCats/Install/Controller.php' => 'b450390cbffdf33ada43c90f7a2e7d3e',
                'library/ThemeHouse/ResCats/Listener/LoadClass.php' => 'c07d68f5d39d1c0df1dcf0f414db3e1d',
                'library/ThemeHouse/ResCats/ModeratorLogHandler/Category.php' => 'deb4b4ce0f7f7871b127ea4e383b9d47',
                'library/ThemeHouse/ResCats/ViewPublic/Category/Add.php' => '1b1b9f3dc201e9426ffc644b8952d3e2',
                'library/ThemeHouse/Install.php' => '18f1441e00e3742460174ab197bec0b7',
                'library/ThemeHouse/Install/20151109.php' => '2e3f16d685652ea2fa82ba11b69204f4',
                'library/ThemeHouse/Deferred.php' => 'ebab3e432fe2f42520de0e36f7f45d88',
                'library/ThemeHouse/Deferred/20150106.php' => 'a311d9aa6f9a0412eeba878417ba7ede',
                'library/ThemeHouse/Listener/ControllerPreDispatch.php' => 'fdebb2d5347398d3974a6f27eb11a3cd',
                'library/ThemeHouse/Listener/ControllerPreDispatch/20150911.php' => 'f2aadc0bd188ad127e363f417b4d23a9',
                'library/ThemeHouse/Listener/InitDependencies.php' => '8f59aaa8ffe56231c4aa47cf2c65f2b0',
                'library/ThemeHouse/Listener/InitDependencies/20150212.php' => 'f04c9dc8fa289895c06c1bcba5d27293',
                'library/ThemeHouse/Listener/LoadClass.php' => '5cad77e1862641ddc2dd693b1aa68a50',
                'library/ThemeHouse/Listener/LoadClass/20150518.php' => 'f4d0d30ba5e5dc51cda07141c39939e3',
            ));
    }
}