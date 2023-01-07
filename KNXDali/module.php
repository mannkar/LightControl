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

        $_vii = 1;
            foreach     (PrimTrigger as &$_vvalue){
                echo $_vvalue;
            //CreateEvent($_vvalue, $_vii);
            $_vii++;
            

            };

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
