<?php

class ThemeHouse_ResCats_Install_Controller extends ThemeHouse_Install
{

    protected $_resourceManagerUrl = 'http://xenforo.com/community/resources/resource-categories.3961/';

    /**
     *
     * @see ThemeHouse_Install::_getPrerequisites()
     */
    protected function _getPrerequisites()
    {
        return array(
            'XenResource' => '1010000'
        );
    }

    protected function _getContentTypeFields()
    {
        return array(
            'resource_category' => array(
                'moderator_log_handler_class' => 'ThemeHouse_ResCats_ModeratorLogHandler_Category'
            )
        );
    }
}