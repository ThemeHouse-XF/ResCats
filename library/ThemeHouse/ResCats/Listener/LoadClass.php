<?php

class ThemeHouse_ResCats_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_ResCats' => array(
                'controller' => array(
                    'XenResource_ControllerPublic_Resource'
                ),
                'helper' => array(
                    'XenResource_ControllerHelper_Resource'
                ),
                'model' => array(
                    'XenResource_Model_Category'
                ),
            ),
        );
    } /* END _getExtendedClasses */

    public static function loadClassController($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_ResCats_Listener_LoadClass', $class, $extend, 'controller');
    } /* END loadClassController */

    public static function loadClassHelper($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_ResCats_Listener_LoadClass', $class, $extend, 'helper');
    } /* END loadClassHelper */

    public static function loadClassModel($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_ResCats_Listener_LoadClass', $class, $extend, 'model');
    } /* END loadClassModel */
}