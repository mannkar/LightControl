<?
// Klassendefinition
class KNXDali extends IPSModule {
    // Überschreibt die interne IPS_Create($id) Funktion
    public function Create() {
        // Diese Zeile nicht löschen.
        parent::Create();

        $this->RegisterPropertyInteger("PointOfLightDimm", 12345);
        $this->RegisterPropertyInteger("PointOfLightOO", 0);
        $this->RegisterPropertyString("PrimTrigger",'[]');
        $this->RegisterPropertyString("SecTrigger", '[]');
        $this->RegisterPropertyInteger("WeeklyTimeTableEventID", 0);
        $this->RegisterPropertyInteger("HolidayIndicatorID",0);
        $this->RegisterPropertyInteger("DayUsedWhenHoliday",0);
        $this->RegisterPropertyString("PrimDimVal", '[]');
        
        $this->RegisterPropertyInteger("SecDimVal", 50);


        $this->RegisterVariableBoolean('Active', $this->Translate('Active'), '~Switch');
        $this->EnableAction('Active');
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    // Überschreibt die intere IPS_ApplyChanges($id) Funktion
    public function ApplyChanges() {
        // Diese Zeile nicht löschen
        parent::ApplyChanges();

        //$variables = json_decode($this->ReadPropertyString('PrimTrigger'));

        
        //Add references
        foreach ($this->GetReferenceList() as $referenceID)
            {
                $this->UnregisterReference($referenceID);
            }
        if ($this->ReadPropertyInteger('PointOfLightDimm') != 0)
            {
                $this->RegisterReference($this->ReadPropertyInteger('PointOfLightDimm'));
            }
        if ($this->ReadPropertyInteger('WeeklyTimeTableEventID') != 0)
            {
                $this->RegisterReference($this->ReadPropertyInteger('WeeklyTimeTableEventID'));
            }
        if ($this->ReadPropertyInteger('HolidayIndicatorID') != 0)
            {
                $this->RegisterReference($this->ReadPropertyInteger('HolidayIndicatorID'));
            }

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

    public function SetActive(bool $Active)
    {
        
        //Modul aktivieren
        SetValue($this->GetIDForIdent('Active'), $Active);
        return true;
    }

    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'Active':
                $this->SetActive($Value);
                break;

            default:
                throw new Exception('Invalid Ident');
        }
    }
}

?>
