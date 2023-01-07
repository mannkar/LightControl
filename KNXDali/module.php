<?
// Klassendefinition
class KNXDali extends IPSModule {
    // Überschreibt die interne IPS_Create($id) Funktion
    public function Create() {
        // Diese Zeile nicht löschen.
        parent::Create();

        $this->RegisterPropertyInteger("PointOfLightDimm", 12345);
        $this->RegisterPropertyInteger("PointOfLightOO", 0);
        $this->RegisterPropertyString("PrimTrigger", '[]');
        $this->RegisterPropertyString("SecTrigger", '[]');
        $this->RegisterPropertyInteger("WeeklyTimeTableEventID", 0);
        $this->RegisterPropertyString("PrimDimVal", '[]');


        $this->RegisterPropertyInteger("SecDimVal", 50);

    }
    // Überschreibt die intere IPS_ApplyChanges($id) Funktion
    public function ApplyChanges() {
        // Diese Zeile nicht löschen
        parent::ApplyChanges();

        //***********************************************************
        //Create our trigger
        $_vii = 1;
        foreach     (PrimTrigger as &SourceVariable){

            echo SourceVariable;
            /*
			if(IPS_VariableExists($this->ReadPropertyInteger("SourceVariable"))) {
				$eid = @IPS_GetObjectIDByIdent("SourceTrigger".$_vii, $this->InstanceID);
				if($eid === false) {
					$eid = IPS_CreateEvent(0 );
					IPS_SetParent($eid, $this->InstanceID);
					IPS_SetIdent($eid, "SourceTrigger".$_vii);
					IPS_SetName($eid, "Trigger for #".$this->ReadPropertyInteger("SourceVariable"));
				}
				IPS_SetEventTrigger($eid, 0, $this->ReadPropertyInteger("SourceVariable"));
				IPS_SetEventScript($eid, "SetValue(IPS_GetObjectIDByIdent(\"Value\", \$_IPS['TARGET']), UMR_Calculate(\$_IPS['TARGET'], \$_IPS['VALUE']));");
				IPS_SetEventActive($eid, true);
                */
                $_vii++;
			}
        }

        //***********************************************************
    }
    /**
    * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
    * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
    *
    * ABC_MeineErsteEigeneFunktion($id);
    *
    */
    public function MeineErsteEigeneFunktion() {
        // Selbsterstellter Code
    }
}

?>
