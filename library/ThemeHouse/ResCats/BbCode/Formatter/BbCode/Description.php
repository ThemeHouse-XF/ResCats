<?php

/**
 * Filters BB codes based on rules.
 * BB codes violating these rules will be stripped.
 *
 * @package XenForo_BbCode
 */
class ThemeHouse_ResCats_BbCode_Formatter_BbCode_Description extends XenForo_BbCode_Formatter_BbCode_Filter
{

    public function configureForDescription()
    {
        foreach ($this->_tags as $tagName => $tag) {
            if (!in_array($tagName, array(
                'b',
                'i',
                'u'
            ))) {
                $this->disableTags($tagName);
            }
        }
    }
}