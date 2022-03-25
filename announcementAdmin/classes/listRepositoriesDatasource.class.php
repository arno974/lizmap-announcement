
<?php

require_once (JELIX_LIB_PATH.'forms/jFormsDatasource.class.php');

class listRepositoriesDatasource implements jIFormsDatasource{
  
  protected $formId = 0;
  protected $data = array();

  function __construct($id)  {
    foreach (lizmap::getRepositoryList() as $repo) {
      $rep = lizmap::getRepository($repo);
      $this->data[$repo] = (string) $rep->getData('label');
    }    
  }

  public function getData($form)  {
    return ($this->data);
  }

  public function getLabel($key)  {
    if(isset($this->data[$key]))
      return $this->data[$key];
    else
      return null;
  }

}