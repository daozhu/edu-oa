<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Office]].
 *
 * @see Office
 */
class OfficeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Office[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Office|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
