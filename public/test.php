<?php
function getSKYD($aGroups){
	$nGroups = array();
	foreach($aGroups as $sGroup){
		preg_match("/cn=([^,]+)/", $sGroup, $treffer);
		$sCN = explode("=", $treffer[0])[1];
		if(substr($sCN,0,4) == 'SKYD')
		{
			array_push($nGroups, $sCN);
		}
		
	}	
	return $nGroups;
}

	$aGroups = array(
		"cn=SKYD-Users,ou=Users,o=Cham",
		"cn=asdf,ou=Users,o=Cham",
		"cn=wer,ou=Users,o=Cham",
		"cn=SKYD-Admins,ou=Users,o=Cham",
		"cn=dfg,ou=Users,o=Cham",
		"cn=erterz,ou=Users,o=Cham",
	);
	
	print_r(getSKYD($aGroups));



?>