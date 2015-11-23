<?php

class ThemeHouse_ResCats_ModeratorLogHandler_Category extends XenForo_ModeratorLogHandler_Abstract
{

    protected function _log(array $logUser, array $content, $action, array $actionParams = array(), $parentContent = null)
    {
        $dw = XenForo_DataWriter::create('XenForo_DataWriter_ModeratorLog');
        $dw->bulkSet(
            array(
                'user_id' => $logUser['user_id'],
                'content_type' => 'resource_category',
                'content_id' => $content['resource_category_id'],
                'content_user_id' => $logUser['user_id'],
                'content_username' => $logUser['username'],
                'content_title' => $content['category_title'],
                'content_url' => XenForo_Link::buildPublicLink('resources/categories', $content),
                'discussion_content_type' => 'resource_category',
                'discussion_content_id' => $content['resource_category_id'],
                'action' => $action,
                'action_params' => $actionParams
            ));
        $dw->save();
        
        return $dw->get('moderator_log_id');
    }
}