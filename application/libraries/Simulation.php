<?php 
 
// script de la course (array javascript)
/*

SCRIPT
	{
		chocobos: 
			[
				{
					name: string,
					colour: string
				},
				{
				
				}
			],
		tours:
			[
				{ TOUR 1
					distances: 
						{
							chocobo: string,
							allure: string,
							distance: string,
							speed: string 
						}
					events:
						[
							{
								title: string
								allure: string
								content: string
							},
							{}
						]
				},
				{ TOUR 2
					
				} 
			]
	}


*/
class Simulation {
	
	// Informations sur la course (object php)
	protected $race;
	
	public function run ( $race ) 
	{ 
		// ------
		//
		// ETAPE 1 : Initialisation des variables de classe
		//
		// ------
		
		$this->race = $race;
		
		$nbr_chocobos = 0;
		$chocobos = array();
		foreach ($race->chocobos as $chocobo)
		{
			$infos = $chocobo->as_array();
			$infos['initiative'] = 100;
			$infos['distance'] = 0;
			$infos['course_last'] = 0;
			$infos['course_cumul'] = 0;
			$infos['tours'] = 0;
			$infos['allure'] = 'normal';
			$infos['course_min'] = 1;
			$infos['course_avg'] = (1 + $infos['speed'] / 2) / 2;
			$infos['course_max'] = $infos['speed'] / 2;
			$infos['arrived'] = FALSE;
				
			$chocobos[] = $infos;
			
			$script_chocobo[] = "{name:'" . $infos['name'] . "',colour:'" . $infos['colour'] . "'}";
			
			$nbr_chocobos++;
		}
		
		$script_chocobos = '[' . implode(',', $script_chocobo) . ']';
		
		// ------
		//
		// ETAPE 2 : Simulation
		//
		// ------
		
		$tour = 0;
		
		$nbr_chocobos_arrived = 0;
		
		while ($nbr_chocobos_arrived < $nbr_chocobos) // tant que tous les chocobos ne sont pas arrivés
		{
			// Nouveau tour	
			$tour++;
			
			// réordonne les chocobos selon leur initiative
			//$this->order($chocobos);
			
			// initialistion des scripts
			$script_point = array();
			$script_event = array();
			
			// génération du script
			foreach ($chocobos as &$chocobo)
			{
				if ($chocobo['arrived']) { continue; }
				
				// le chocobo utilise ou pas une de ses compétences
				//$chocobo->use_competence();
				
				
				// fais avancer le chocobo
				if ($chocobo['allure'] == 'normal') 
				{
					if ($chocobo['course_last'] <= $chocobo['course_avg']) // Adjustable
					{
						$course_alea = rand(3 * $chocobo['speed'], 5 * $chocobo['speed']) /100;
						$course_current = $chocobo['course_last'] + $course_alea; // Adjustable
					}
					else
					{
						$course_alea = rand(3 * $chocobo['speed'], 5 * $chocobo['speed']) /100;
						$course_current = $chocobo['course_last'] - $course_alea; // Adjustable
					}
				}
				
				$name = $chocobo['name'];
				$distance = min($chocobo['distance'] + $course_current, $race->length);
				$distance = number_format($distance, 2, '.', ''); 
				$speed = number_format($course_current, 2, '.', '');
				$allure = $chocobo['allure'];
				
				$script_point[] = "{
					chocobo:'" . $name . "',
					distance:'" . $distance . "',
					speed:'" . $speed . "',
					allure:'" . $allure . "'
				}";
				
				$chocobo['distance'] = $distance;
				$chocobo['course_last'] = $course_current;
				$chocobo['course_cumul'] += $course_current;
				$chocobo['tours'] ++;
				
				// On regarde si le chocobo a traversé la ligne d'arrivée
				if ($chocobo['distance'] >= $race->length)
				{
					$nbr_chocobos_arrived++;
					$position = Kohana::lang("race.pos$nbr_chocobos_arrived");
					$script_event[] = "{chocobo:'" . $chocobo['name'] . "',title:'$position',allure:'happy'}";
					$chocobo['position'] = $nbr_chocobos_arrived;
					$chocobo['arrived'] = TRUE;
				}
				
				// le chocobo met à jour son score d'initiative
				//$chocobo->update_initiative();
			
			}
			
			$script_points = '[' . implode(',', $script_point) . ']';
			$script_events = '[' . implode(',', $script_event) . ']';
			$script_tour[] = '{points:' . $script_points . ',events:' . $script_events . '}';
		}
		
		$script_tours = '[' . implode(',', $script_tour) . ']';
		
		// ------
		//
		// ETAPE 3 : Enregistrement du script
		//
		// ------
		
		foreach ($chocobos as &$chocobo)
		{
			$result = ORM::factory('result');
			$result->race_id = $race->id;
			$result->chocobo_id = $chocobo['id'];
			$result->name = $chocobo['name'];
			$result->position = $chocobo['position'];
			$result->tours = $chocobo['tours'];
			$result->avg_speed = $chocobo['course_cumul'] / $chocobo['tours'];
			$result->save();
			
			$c = ORM::factory('chocobo', $chocobo['id']);
			$c->race_id = 0;
			$c->set_exp($chocobo['position']);
			$c->hp = $chocobo['hp'];
			$c->mp = $chocobo['mp'];
			$c->set_rage($chocobo['rage']);
			//$c->set_fame($nbr_chocobos);
			$c->save();
		}
		
		$race->script = '{chocobos:' . $script_chocobos . ',tours:' . $script_tours  . '}';
		$race->save();
	}
	
	// réordonne les chocobos par initiative
	public function order($chocobos)
	{
		foreach ($chocobos as $key) {
	        $sort_initiative[] = $key['initiative'];
	    }
	
	    array_multisort($sort_initiative, SORT_DESC, $chocobos);
	}

}
