<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../core/php/core.inc.php';

class widgets {
  /*     * *************************Attributs****************************** */
  
  private $id;
  private $name;
  private $type;
  private $subtype;
  private $template;
  private $replace;
  private $test;
  private $_changed = false;
  
  /*     * ***********************Méthodes statiques*************************** */
  
  public static function all() {
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM widgets';
    return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function byId($_id) {
    $values = array(
      'id' => $_id,
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM widgets
    WHERE id=:id';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function byTypeSubtypeAndName($_type, $_subtype, $_name) {
    $values = array(
      'type' => $_type,
      'subtype' => $_subtype,
      'name' => $_name,
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM widgets
    WHERE type=:type
    AND subtype=:subtype
    AND name=:name';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function listTemplate(){
    $return = array();
    $files = ls(__DIR__ . '/../template/dashboard', 'cmd.*', false, array('files', 'quiet'));
    foreach ($files as $file) {
      $informations = explode('.', $file);
      if(count($informations) < 4){
        continue;
      }
      if(stripos($informations[3],'tmpl') === false){
        continue;
      }
      if (!isset($return[$informations[1]])) {
        $return[$informations[1]] = array();
      }
      if (!isset($return[$informations[1]][$informations[2]])) {
        $return[$informations[1]][$informations[2]] = array();
      }
      if (isset($informations[3])) {
        $return[$informations[1]][$informations[2]] = $informations[3];
      }
    }
    return $return;
  }
  
  public static function getTemplateConfiguration($_template){
    if(!file_exists(__DIR__ . '/../template/dashboard/'.$_template.'.html')){
      return;
    }
    $return = array('test' => false);
    $template = file_get_contents(__DIR__ . '/../template/dashboard/'.$_template.'.html');
    if(strpos($template,'#test#') !== false){
      $return['test'] = true;
    }
    preg_match_all("/#_([a-zA-Z_]*)_#/", $template, $matches);
    if (count($matches[1]) == 0) {
      return $return ;
    }
    $return['replace'] = $matches[1];
    return $return;
  }
  
  /*     * *********************Méthodes d'instance************************* */
  
  public function save() {
    DB::save($this);
    return true;
  }
  
  public function remove() {
    DB::remove($this);
  }
  
  /*     * **********************Getteur Setteur*************************** */
  
  public function getId() {
    return $this->id;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getType() {
    return $this->type;
  }
  
  public function getSubtype() {
    return $this->subtype;
  }
  
  public function getTemplate() {
    return $this->template;
  }
  
  public function getReplace($_key = '', $_default = '') {
    return utils::getJsonAttr($this->replace, $_key, $_default);
  }
  
  public function getTest($_key = '', $_default = '') {
    return utils::getJsonAttr($this->test, $_key, $_default);
  }
  
  public function setId($_id = '') {
    $this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
    $this->id = $_id;
    return $this;
  }
  
  public function setName($_name) {
    $_name = str_replace(array('&', '#', ']', '[', '%', "'"), '', $_name);
    $this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
    $this->name = $_name;
    return $this;
  }
  
  public function setType($_type) {
    $this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
    $this->type = $_type;
    return $this;
  }
  
  public function setSubtype($_subtype) {
    $this->_changed = utils::attrChanged($this->_changed,$this->subtype,$_subtype);
    $this->subtype = $_subtype;
    return $this;
  }
  
  public function setTemplate($_template) {
    $this->_changed = utils::attrChanged($this->_changed,$this->template,$_template);
    $this->template = $_template;
    return $this;
  }
  
  public function setReplace($_key, $_value) {
    $replace = utils::setJsonAttr($this->replace, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->replace,$replace);
    $this->replace = utils::setJsonAttr($this->replace, $_key, $_value);
    return $this;
  }
  
  public function setTest($_key, $_value) {
    $test = utils::setJsonAttr($this->test, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->test,$test);
    $this->test = utils::setJsonAttr($this->test, $_key, $_value);
    return $this;
  }
}
