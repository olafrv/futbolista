<?php
    class Groupping extends AppModel {
       var $hasMany = array('PseudoTeam');
       var $belongsTo = array('Fase');
       var $displayField = 'title';
       var $virtualFields = array('title_id' => 'CONCAT(Groupping.title, \' (\', Groupping.id, \')\')');
    }

