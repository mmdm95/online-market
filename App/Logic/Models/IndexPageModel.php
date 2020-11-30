<?php

namespace App\Logic\Models;

class IndexPageModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = self::TBL_MAIN_SLIDER;

    /**
     * @return array
     */
    public function getMainSlider(): array
    {
        return parent::get(['title', 'note', 'image', 'link'], null, [], ['priority DESC']);
    }
}