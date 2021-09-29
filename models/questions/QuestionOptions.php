<?php
namespace App\Models\Questions;

use App\Classes\Database;
use App\Models\Option;

class QuestionOptions extends Question
{
    public $options = array();

    public function loadData($data)
    {
        parent::loadData($data);
        if(isset($data['option'])) //this check might be redunat, it's only being chcecked when it's already be verified to have otptions
        {
            foreach(array_keys($data['option']) as $key)
            {

                $option = new Option();
                $option->setId($key);
                $option->choice = $data['option'][$key];
                $this->options[] = $option;
            }
        }
    }

    public function loadDataFromDB($data)
    {
        parent::loadData($data);
        $option = new Option();
        $option->loadData($data);
        $this->options[] = $option;

    }

    public function addOption($data)
    {
        $option = new Option();
        $option->loadData($data);
        $this->options[] = $option;
    }

    public function save($surv_id)
    {
        $db = Database::$db;
        parent::save($surv_id);
        foreach($this->options as $option)
        {
//           $db->createOption($option, $this->id);
            $option->save($this->id);

        }
    }

    public function update()
    {
        $db = Database::$db;
        parent::update();
        foreach($this->options as $option)
        {
//           $db->createOption($option, $this->id);
            $option->update($this);

        }
    }

}

