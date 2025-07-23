<?
// Klassendefinition
class KNXDali extends IPSModule {
    // Überschreibt die interne IPS_Create($id) Funktion
    public function Create() {
        // Diese Zeile nicht löschen.
        parent::Create();

        $this->RegisterPropertyInteger("PointOfLightDimm", 1);
        $this->RegisterPropertyInteger("PointOfLightOO", 0);
        $this->RegisterPropertyString("PrimTriggers",'[]');
        $this->RegisterPropertyString("SecTriggers", '[]');
        $this->RegisterPropertyInteger("WeeklyTimeTableEventID", 0);
        $this->RegisterPropertyInteger("HolidayIndicatorID",0);
        $this->RegisterPropertyInteger("DayUsedWhenHoliday",0);
        $this->RegisterPropertyString("PrimDimVals", '[]');
        $this->RegisterPropertyInteger("SecDimVal", 50);
        $this->RegisterPropertyInteger("CleanDimVal", 80);

        $this->RegisterVariableBoolean('Active', $this->Translate('Active'), '~Switch');
        $this->EnableAction('Active');
        $this->RegisterVariableBoolean('Cleaning', $this->Translate('Cleaning'), '~Switch');
        $this->EnableAction('Cleaning');
        $this->RegisterVariableFloat('Nominal', $this->Translate('Nominal value'), '', 20);
        $this->EnableAction('Nominal');
        $this->SetValue("Nominal", 20);

    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    // Überschreibt die intere IPS_ApplyChanges($id) Funktion
    public function ApplyChanges() 
    {
        // Diese Zeile nicht löschen
        parent::ApplyChanges();


        //Delete all references in order to re-add them - Alle Referenzen löschen, um sie neu anzulegen
        foreach ($this->GetReferenceList() as $referenceID) {
            $this->UnregisterReference($referenceID);
        }
        
        //Delete all registrations in order to re-add them - Alle Registrierungen löschen, um sie neu anzulegen
        foreach ($this->GetMessageList() as $senderID => $messages) {
            foreach ($messages as $message) {
                $this->UnregisterMessage($senderID, $message);
            }
        }

        //Register update messages and references - Aktualisierungsmeldungen und Referenzen registrieren

        if ($this->ReadPropertyInteger('PointOfLightDimm') != 0)
            { $this->RegisterReference($this->ReadPropertyInteger('PointOfLightDimm')); }

        if ($this->ReadPropertyInteger('PointOfLightOO') != 0)
            { $this->RegisterReference($this->ReadPropertyInteger('PointOfLightOO')); }
        
        if ($this->ReadPropertyInteger('WeeklyTimeTableEventID') != 0)
            { $this->RegisterReference($this->ReadPropertyInteger('WeeklyTimeTableEventID'));
              $this->RegisterMessage($this->ReadPropertyInteger('WeeklyTimeTableEventID'), EM_UPDATE); }
        
        $this->RegisterMessage($this->GetIDForIdent('Cleaning'), VM_UPDATE);

        $inputTriggers = json_decode($this->ReadPropertyString('PrimTriggers'), true);
        foreach ($inputTriggers as $inputTrigger)
            { $triggerID = $inputTrigger['PrimTriggerIDs'];
            $this->RegisterMessage($triggerID, VM_UPDATE);
            $this->RegisterReference($triggerID); }
        
        $inputTriggers = json_decode($this->ReadPropertyString('SecTriggers'), true);
        foreach ($inputTriggers as $inputTrigger)
            { $triggerID = $inputTrigger['SecTriggerIDs'];
            $this->RegisterMessage($triggerID, VM_UPDATE); 
            $this->RegisterReference($triggerID); }

        //Check status column for inputs - Statusspalte für Eingänge prüfen
        $inputTriggers = json_decode($this->ReadPropertyString('PrimTriggers'), true);
        $inputTriggerOkCount = 0;
        foreach ($inputTriggers as $inputTrigger) {
            if ($this->GetTriggerStatus($inputTrigger['PrimTriggerIDs']) == 'OK') {
                $inputTriggerOkCount++;
            }
        }

        //Check status column for inputs - Statusspalte für Eingänge prüfen
        $inputTriggers2 = json_decode($this->ReadPropertyString('SecTriggers'), true);
        $inputTriggerOkCount2 = 0;
        foreach ($inputTriggers2 as $inputTrigger2) {
            if ($this->GetTriggerStatus($inputTrigger2['SecTriggerIDs']) == 'OK') {
                $inputTriggerOkCount2++;
            }
        }
    }

    public function GetConfigurationForm()
    {
        $this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        //Add options to form - Optionen zum Formular hinzufügen
        $jsonForm = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        //Set status column for inputs - Statusspalte für Eingänge setzen
        $inputTriggers = json_decode($this->ReadPropertyString('PrimTriggers'), true);
        foreach ($inputTriggers as $inputTrigger) {
            $jsonForm['elements'][2]['items'][0]['values'][] = [
                'Status' => $this->GetTriggerStatus($inputTrigger['PrimTriggerIDs'])
            ];
        }

        $inputTriggers2 = json_decode($this->ReadPropertyString('SecTriggers'), true);
        foreach ($inputTriggers2 as $inputTrigger2) {
            $jsonForm['elements'][3]['items'][0]['values'][] = [
                'Status' => $this->GetTriggerStatus($inputTrigger2['SecTriggerIDs'])
            ];
        }    
        return json_encode($jsonForm);   
    }

    private function GetTriggerStatus($triggerID)
    {
        $this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        if (!IPS_VariableExists($triggerID)) {
            return 'Missing';
        } elseif (IPS_GetVariable($triggerID)['VariableType'] == VARIABLETYPE_STRING) {
            return 'Bool/Int/Float required';
        } else {
            return 'OK';
        }
    }

    /**
    * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
    * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
    *
    * ABC_MeineErsteEigeneFunktion($id);
    *
    */

    
    public function SetActive(bool $Active)
    {
        //Modul aktivieren
        $this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        $this->SendDebug(__FUNCTION__, "Message SetActive ".$Active, 0);
        SetValue($this->GetIDForIdent('Active'), $Active);
        return true;
    }

    public function SetCleaning(bool $Cleaning)
    {
        //Reinigung aktivieren
        $this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        //$this->SendDebug(__FUNCTION__, "Message from SetCleaning ".$Cleaning, 0);
        SetValue($this->GetIDForIdent('Cleaning'), $Cleaning);
        if (!$Cleaning) //if cleaning gets deactivated, the nominal has to be determinned - Wenn die Reinigung deaktiviert wird, muss der Sollwert wieder ermittelt werden
            {
                $BaseLevel = $this -> CalculateDimmLevel();
                SetValue($this->GetIDForIdent('Nominal'), $BaseLevel);       
            }
        return true;
    }

    public function SetNominal( float $Nominal)
        {
            //Sollwert aktivieren
            $this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
            SetValue($this->GetIDForIdent('Nominal'), $Nominal);
            return true;
        }

    public function RequestAction($Ident, $Value)
    {
        $this->SendDebug(__FUNCTION__,$_IPS['SENDER'].'#1' ,0);
        //$this->SendDebug(__FUNCTION__, "Message from Ident ".$Ident.'#2', 0);
        switch ($Ident) {
            case 'Active':
                $this->SetActive($Value);
                break;
            case 'Cleaning':
                $this->SetCleaning($Value);
                break;
            case 'Nominal':
                $this->SetNominal($Value);
                break;

            default:
                throw new Exception('Invalid Ident');
        }
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data) {
        //$this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        if (GetValue($this->GetIDForIdent('Active')))
        {
            if ($Message == EM_UPDATE)
                {
                    //$this->SendDebug(__FUNCTION__, "Message from SenderID ".$SenderID." with Zeitupdate", 0);
                    $BaseLevel = $this -> CalculateDimmLevel();
                    //$this->SendDebug(__FUNCTION__, "Message from SenderID ".$SenderID." with BaseLevel ".$BaseLevel, 0);
                    SetValue($this->GetIDForIdent('Nominal'), $BaseLevel);
                }

            if ($Message == VM_UPDATE and $SenderID = $this->GetIDForIdent('Cleaning')) {
                 // $this->SendDebug(__FUNCTION__, "Message from SenderID ".$SenderID." with Cleaning Message: ", 0);
                 if (GetValue($this->GetIDForIDent('Cleaning'))== true)
                 {
                    $BaseLevel = $this -> ReadPropertyInteger("CleanDimVal");
                    SetValue($this->GetIDForIdent('Nominal'), $BaseLevel);
                 }
            }

            if ($Message == VM_UPDATE) {
                //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message ".$Message."\r\n Data: ".print_r($Data, true));
                //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message ".$Message."\r\n Data: "."Los ->");
                $idSwitch = $this ->  ReadPropertyInteger("PointOfLightOO");
                $idDimm = $this ->  ReadPropertyInteger("PointOfLightDimm");
                //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message: ". $id);
                //$value = GetValueBoolean ($this -> ReadPropertyInteger("Trigger")); 
                $Level = $this -> TriggerStatus(); // off, low, high
                $Message = "";
                //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message: ".$idDimm. " " . $Message.$Level);
                
                switch ($Level)
                {
                    case "off":
                        //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with OFF Message: ".$idDimm. " " . $Message.$Level);
                        RequestAction ($idDimm, 0);
                        break;
                    
                    case "low":
                        $BaseLevel = GetValueFloat($this->GetIDForIdent('Nominal'));
                        if ($BaseLevel > 0)
                            {
                            //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with LOW Message: ".$idDimm. " " . $Message.$Level);
                            $this->SendDebug(__FUNCTION__, "Message from SenderID ".$SenderID." with LOW Message: ".$idDimm. " " . $Message.$Level, 0);
                            $Lower = $this -> ReadPropertyInteger("SecDimVal");
                            //SetValueInteger ($idDimm, $BaseLevel/100*$Lower);
                            RequestAction ($idDimm, $BaseLevel/100*$Lower);
                            }
                        break;
                    case "high":
                        $BaseLevel = GetValueFloat($this->GetIDForIdent('Nominal'));
                        if ($BaseLevel > 0)
                            {
                            //IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with HIGH Message: ".$idDimm. " " . $Message.$Level);
                            $this->SendDebug(__FUNCTION__, "Message from SenderID ".$SenderID." with HIGH Message: ".$idDimm. " " . $Message.$Level, 0);
                            //SetValueInteger ($idDimm, $BaseLevel);
                            RequestAction ($idDimm, $BaseLevel);
                            }
                        break;
                    }
                //SetValueBoolean($id , $value);               
                }
        }
    }    

    public function TimeTableEvent ()
    {
        //$this->RegisterPropertyInteger("WeeklyTimeTableEventID", 0);
        //$this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        $TimeTable = $this -> ReadPropertyInteger ("WeeklyTimeTableEventID");
        $id = $TimeTable;

        $e = IPS_GetEvent($TimeTable);
        //var_dump($e);
        $actionID = false;
        //Loop through all groups
        foreach($e['ScheduleGroups'] as $g) {
            //Check if group todays current
            if($g['Days'] & date("N") > 0) {
                //Check for actual switchpoint. We us the property, that the switch points are always ascending sorted.
                foreach($g['Points'] as $p) {
                    if(date("H") * 3600 + date("i") * 60 + date("s") >= $p['Start']['Hour'] * 3600 + $p['Start']['Minute'] * 60 + $p['Start']['Second']) {
                        $actionID = $p['ActionID'];
                    } else {
                        break; //As soon its the shwitch point in advance, we can stop
                    }
                }
                break; //As soon we found the current day, we can quit the loop. Every day is member of exactly one group
            }
        }
        return $actionID;
        //var_dump($actionID);

    }

    public function CalculateDimmLevel()
    {
        //$this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        $DayTime = $this -> TimeTableEvent () -1; //
        //IPS_LogMessage("MessageSink", "DayTime Message: " . $DayTime);
        $this->SendDebug(__FUNCTION__, "DayTime Message: " . $DayTime, 0);
        $inputLevel = json_decode($this->ReadPropertyString('PrimDimVals'), true);
        $BaseLevel = $inputLevel [$DayTime] ['SwitchValue'] ;
        //IPS_LogMessage("MessageSink", "Message DImmlevel: " . $dump);
        $Lower = $this -> ReadPropertyInteger("SecDimVal");
        //RequestAction ($idDimm, $BaseLevel/100*$Lower);
        return $BaseLevel;


    }

    public function TriggerStatus ()
    {
        //$this->SendDebug(__FUNCTION__,$_IPS['SENDER'] ,0);
        $PrimTriggerStatus = 0;
        $SecTriggerStatus = 0;
        $SumTriggerStatus = 0;
        $ReturnTriggerStatus = 0;
        $inputTriggers = json_decode($this->ReadPropertyString('PrimTriggers'), true);
        foreach ($inputTriggers as $inputTrigger) {
                $triggerID = $inputTrigger['PrimTriggerIDs'];
                if (GetValue ($triggerID))
                {   $PrimTriggerStatus = $PrimTriggerStatus +100 ; // this prerequisites, that a max of 99 secundary trigger exists
                }
                //IPS_LogMessage("MessageSink", "Message from SenderID "." Test PF TriggerStatus  "." with Message "." "."\r\n Data: ".$triggerID);
        }
        //IPS_LogMessage("MessageSink", "Message from PrimTriggerStatus  "." with Message ".$PrimTriggerStatus);

        $inputTriggers = json_decode($this->ReadPropertyString('SecTriggers'), true);
        foreach ($inputTriggers as $inputTrigger) {
                $triggerID = $inputTrigger['SecTriggerIDs'];
                if (GetValue ($triggerID))
                {  $SecTriggerStatus = $SecTriggerStatus +1 ;
                }
                //IPS_LogMessage("MessageSink", "Message from SenderID "." Test PF TriggerStatus  "." with Message "." "."\r\n Data: ".$triggerID);
        }
        //IPS_LogMessage("MessageSink", "Message from PrimTriggerStatus  "." with Message: ".$SecTriggerStatus);
        $SumTriggerStatus = $PrimTriggerStatus + $SecTriggerStatus;
        //IPS_LogMessage("MessageSink", "Message from SumTriggerStatus  "." with Message: ".$SumTriggerStatus);
        if ($SumTriggerStatus == 0)
        {   $ReturnTriggerStatus = "off";
            //IPS_LogMessage("MessageSink", "Message from Off Status  "." with Message: ".$ReturnTirggerStatus);
        }
        elseif ($SumTriggerStatus < 100)
        {   $ReturnTriggerStatus = "low";
            //IPS_LogMessage("MessageSink", "Message from LOW Status  "." with Message: ".$ReturnTirggerStatus);
        }
        elseif ($SumTriggerStatus >= 100)
        {   $ReturnTriggerStatus = "high";
            //IPS_LogMessage("MessageSink", "Message from hight Status  "." with Message: ".$ReturnTirggerStatus);
        }

        //IPS_LogMessage("MessageSink", "Message from ReturnTriggerStatus  "." with Message: ".$ReturnTirggerStatus);
        return $ReturnTriggerStatus;

    }
}

?>
