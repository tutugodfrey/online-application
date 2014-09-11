<?php
class OnlineappUpdateStateDefaultValsSwitchAbbrevAndFullname extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		if ($direction == 'up') {
			$this->db->execute(
				"UPDATE onlineapp_template_fields
				   SET default_value = 'Alabama::AL,Alaska::AK,Arizona::AZ,Arkansas::AR,California::CA,Colorado::CO,Connecticut::CT,Delaware::DE,District Of Columbia::DC,Florida::FL,Georgia::GA,Hawaii::HI,Idaho::ID,Illinois::IL,Indiana::IN,Iowa::IA,Kansas::KS,Kentucky::KY,Louisiana::LA,Maine::ME,Maryland::MD,Massachusetts::MA,Michigan::MI,Minnesota::MN,Mississippi::MS,Missouri::MO,Montana::MT,Nebraska::NE,Nevada::NV,New Hampshire::NH,New Jersey::NJ,New Mexico::NM,New York::NY,North Carolina::NC,North Dakota::ND,Ohio::OH,Oklahoma::OK,Oregon::OR,Pennsylvania::PA,Rhode Island::RI,South Carolina::SC,South Dakota::SD,Tennessee::TN,Texas::TX,Utah::UT,Vermont::VT,Virginia::VA,Washington::WA,West Virginia::WV,Wisconsin::WI,Wyoming::WY'
				 WHERE merge_field_name ilike '%state%'
				   AND default_value = 'AL::Alabama,AK::Alaska,AZ::Arizona,AR::Arkansas,CA::California,CO::Colorado,CT::Connecticut,DE::Delaware,DC::District Of Columbia,FL::Florida,GA::Georgia,HI::Hawaii,ID::Idaho,IL::Illinois,IN::Indiana,IA::Iowa,KS::Kansas,KY::Kentucky,LA::Louisiana,ME::Maine,MD::Maryland,MA::Massachusetts,MI::Michigan,MN::Minnesota,MS::Mississippi,MO::Missouri,MT::Montana,NE::Nebraska,NV::Nevada,NH::New Hampshire,NJ::New Jersey,NM::New Mexico,NY::New York,NC::North Carolina,ND::North Dakota,OH::Ohio,OK::Oklahoma,OR::Oregon,PA::Pennsylvania,RI::Rhode Island,SC::South Carolina,SD::South Dakota,TN::Tennessee,TX::Texas,UT::Utah,VT::Vermont,VA::Virginia,WA::Washington,WV::West Virginia,WI::Wisconsin,WY::Wyoming';"
                	);
		}

		if ($direction == 'down') {
			$this->db->execute(
				"UPDATE onlineapp_template_fields
				   SET default_value = 'AL::Alabama,AK::Alaska,AZ::Arizona,AR::Arkansas,CA::California,CO::Colorado,CT::Connecticut,DE::Delaware,DC::District Of Columbia,FL::Florida,GA::Georgia,HI::Hawaii,ID::Idaho,IL::Illinois,IN::Indiana,IA::Iowa,KS::Kansas,KY::Kentucky,LA::Louisiana,ME::Maine,MD::Maryland,MA::Massachusetts,MI::Michigan,MN::Minnesota,MS::Mississippi,MO::Missouri,MT::Montana,NE::Nebraska,NV::Nevada,NH::New Hampshire,NJ::New Jersey,NM::New Mexico,NY::New York,NC::North Carolina,ND::North Dakota,OH::Ohio,OK::Oklahoma,OR::Oregon,PA::Pennsylvania,RI::Rhode Island,SC::South Carolina,SD::South Dakota,TN::Tennessee,TX::Texas,UT::Utah,VT::Vermont,VA::Virginia,WA::Washington,WV::West Virginia,WI::Wisconsin,WY::Wyoming'
				 WHERE merge_field_name ilike '%state%'
				   AND default_value = 'Alabama::AL,Alaska::AK,Arizona::AZ,Arkansas::AR,California::CA,Colorado::CO,Connecticut::CT,Delaware::DE,District Of Columbia::DC,Florida::FL,Georgia::GA,Hawaii::HI,Idaho::ID,Illinois::IL,Indiana::IN,Iowa::IA,Kansas::KS,Kentucky::KY,Louisiana::LA,Maine::ME,Maryland::MD,Massachusetts::MA,Michigan::MI,Minnesota::MN,Mississippi::MS,Missouri::MO,Montana::MT,Nebraska::NE,Nevada::NV,New Hampshire::NH,New Jersey::NJ,New Mexico::NM,New York::NY,North Carolina::NC,North Dakota::ND,Ohio::OH,Oklahoma::OK,Oregon::OR,Pennsylvania::PA,Rhode Island::RI,South Carolina::SC,South Dakota::SD,Tennessee::TN,Texas::TX,Utah::UT,Vermont::VT,Virginia::VA,Washington::WA,West Virginia::WV,Wisconsin::WI,Wyoming::WY';"
                	);
		}

		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		return true;
	}
}
