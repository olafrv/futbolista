<?php

class FutbolGuiHelper extends AppHelper {

	var $helpers = array('Form', 'Session', 'Js'=>array('Jquery'), 'Html', 'Time');

	function makeMatchForm(
		$competition_list, $competition_id, 
			$fase_list, $fase_id, 
				$groupping_list, $groupping_id, 
					$match_list, $match_id,
						$model_name, $action, $header=""){
	
		$html  = $this->Form->create($model_name, array('action' => $action, 'id'=> $model_name.'Form'));
		$html .= $header;
/*
		$html .= $this->Form->select('competition_id', $competition_list, $competition_id, array(
			'empty'=>false
		));
		$html .= '&nbsp;&nbsp;' . $this->Form->select('fase_id', $fase_list, $fase_id, array(
				'empty'=> false,
				'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
		$html .= '&nbsp;&nbsp;' . $this->Form->select('groupping_id', $groupping_list, $groupping_id, array(
				'empty'=> false
				, 'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
*/
		$html .= $this->Form->select('competition_id', $competition_list, $competition_id, array(
			'empty'=>false
			, 'onchange' => 
				"var f=Jolaf.gebi('".$model_name."Form'); 
				 var input_f=Jolaf.gebi('".$model_name."FaseId'); input_f.selectedIndex = -1;
				 var input_g=Jolaf.gebi('".$model_name."GrouppingId'); input_g.selectedIndex = -1;
				 var input_m=Jolaf.gebi('".$model_name."MatchId'); input_m.selectedIndex = -1;
				 f.submit(); return false;"
		));
	
		$html .= '&nbsp;&nbsp;' . $this->Form->select('fase_id', $fase_list, $fase_id, array(
				'empty'=> false
				, 'onchange' => 
					"var f=Jolaf.gebi('".$model_name."Form'); 
					 var input_g=Jolaf.gebi('".$model_name."GrouppingId'); input_g.selectedIndex = -1;
				 	 var input_m=Jolaf.gebi('".$model_name."MatchId'); input_m.selectedIndex = -1;
					 f.submit(); return false;"
		));


		$html .= '&nbsp;&nbsp;' . $this->Form->select('groupping_id', $groupping_list, $groupping_id, array(
				'empty'=> false
				, 'onchange' => 
					"var f=Jolaf.gebi('".$model_name."Form'); 
				 	 var input_m=Jolaf.gebi('".$model_name."MatchId'); input_m.selectedIndex = -1;
					 f.submit(); return false;"
		));

		$html .= '&nbsp;&nbsp;' . $this->Form->select('match_id', $match_list, $match_id, array(
				'empty'=> false,
				'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
		return $html;
	}

	function makeFixtureForm(
		$competition_list, $competition_id, 
			$fase_list, $fase_id, 
				$groupping_list, $groupping_id, $groupping_empty, 
					$fixture_list, $fixture, $fixture_empty, 
						$model_name, $action, $header=""){
		
		$html  = $this->Form->create($model_name, array('action' => $action, 'id'=> $model_name.'Form'));
		$html .= $header;
		$html .= $this->Form->select('competition_id', $competition_list, $competition_id, array(
			'empty'=>false
			, 'onchange' => 
				"var f=Jolaf.gebi('".$model_name."Form'); 
				 var input_f=Jolaf.gebi('".$model_name."FaseId'); input_f.selectedIndex = -1;
				 var input_g=Jolaf.gebi('".$model_name."GrouppingId'); input_g.selectedIndex = -1;
				 var input_g=Jolaf.gebi('".$model_name."Fixture'); input_g.selectedIndex = -1;
				 f.submit(); return false;"
		));
	
		$html .= '&nbsp;&nbsp;' . $this->Form->select('fase_id', $fase_list, $fase_id, array(
				'empty'=> false
				, 'onchange' => 
					"var f=Jolaf.gebi('".$model_name."Form'); 
					 var input_g=Jolaf.gebi('".$model_name."GrouppingId'); input_g.selectedIndex = -1;
					 var input_g=Jolaf.gebi('".$model_name."Fixture'); input_g.selectedIndex = -1;
					 f.submit(); return false;"
		));
		$html .= '&nbsp;&nbsp;' . $this->Form->select('groupping_id', $groupping_list, $groupping_id, array(
				'empty'=> $groupping_empty,
				'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
		$fixture_array = array();
		foreach($fixture_list as $index => $kickoff){
			$fixture_array[$kickoff] = $this->Time->format('d-m-Y', $kickoff);
		}
		$html .= '&nbsp;&nbsp;' . $this->Form->select('fixture', $fixture_array, $fixture, array(
				'empty'=> $fixture_empty
				,'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
		return $html;
	}


	
	function makeGrouppingForm(
		$competition_list, $competition_id, 
			$fase_list, $fase_id, 
				$groupping_list, $groupping_id, $groupping_empty, 
					$model_name, $action, $header=""){
		
		$html  = $this->Form->create($model_name, array('action' => $action, 'id'=> $model_name.'Form'));
		$html .= $header;
		$html .= $this->Form->select('competition_id', $competition_list, $competition_id, array(
			'empty'=>false
			, 'onchange' => 
				"var f=Jolaf.gebi('".$model_name."Form'); 
				 var input_f=Jolaf.gebi('".$model_name."FaseId'); input_f.selectedIndex = -1;
				 var input_g=Jolaf.gebi('".$model_name."GrouppingId'); input_g.selectedIndex = -1;
				 f.submit(); return false;"
		));
	
		$html .= '&nbsp;&nbsp;' . $this->Form->select('fase_id', $fase_list, $fase_id, array(
				'empty'=> false
				, 'onchange' => 
					"var f=Jolaf.gebi('".$model_name."Form'); 
					 var input_g=Jolaf.gebi('".$model_name."GrouppingId'); input_g.selectedIndex = -1;
					 f.submit(); return false;"
		));
		$html .= '&nbsp;&nbsp;' . $this->Form->select('groupping_id', $groupping_list, $groupping_id, array(
				'empty'=> $groupping_empty,
				'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
		return $html;
	}


	function makeFaseForm(
		$competition_list, $competition_id, 
			$fase_list, $fase_id, 
				$model_name, $action, $header=""){
		$html  = $this->Form->create($model_name, array('action' => $action, 'id'=> $model_name.'Form'));
		$html .= $header;
		$html .= $this->Form->select('competition_id', $competition_list, $competition_id, array(
			'empty'=>false
			, 'onchange' => 
				"var f=Jolaf.gebi('".$model_name."Form'); 
				 var input_f=Jolaf.gebi('".$model_name."FaseId'); input_f.selectedIndex = -1;
				 f.submit(); return false;"
		));
	
		$html .= '&nbsp;&nbsp;' . $this->Form->select('fase_id', $fase_list, $fase_id, array(
				'empty'=> false,
				'onchange' => "javascript:f=Jolaf.gebi('".$model_name."Form'); f.submit();"
		));
		return $html;
	}

	function makeCompetitionForm(
		$competition_list, $competition_id, 
			$model, $controller, $action, $header=""){
		$url = '/' . $controller . '/' . $action;
		$html = $this->Form->create(
				$model, 
				array(
					'url'=>$url, 'id'=> $model.'Form'
				)
			);
		$html .= $this->Form->select('competition_id', $competition_list, $competition_id, array(
			'empty'=>false
			, 'onchange' => "javascript:f=Jolaf.gebi('".$model."Form'); f.submit();"
		));
		return $header . $html;
	}

	
} 
