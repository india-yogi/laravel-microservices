<?php 
namespace App\Helpers;

class Email_message {
    public $connection;
    public $messageNumber;    
    public $bodyHTML = '';
    public $bodyPlain = '';
    public $attachments;
    public $getAttachments = true;
    public $allowedExtensions = [
        'jpeg','jpg','png','gif'
    ];

    public function __construct($config_data = array()) {

        $this->connection = $config_data['connection'];
        $this->messageNumber = isset($config_data['message_no'])?$config_data['message_no']:1;
    }

    public function fetch() {

        $structure = @imap_fetchstructure($this->connection, $this->messageNumber);
        if(!$structure) {
            return false;
        }
        else {
            if(isset($structure->parts)){
                $this->recurse($structure->parts);
            }
            return true;
        }

    }

    public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true) {

        foreach($messageParts as $part) {

            $partNumber = $prefix . $index;

            if($part->type == 0) {
                if($part->subtype == 'PLAIN') {
                    $this->bodyPlain .= $this->getPart($partNumber, $part->encoding);
                }
                else {
                    $this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
                }
            }
            elseif($part->type == 2) {
                $msg = new Email_message(array('connection' =>$this->connection,'message_no'=>$this->messageNumber));
                $msg->getAttachments = $this->getAttachments;
                if(isset($part->parts)){
                    $msg->recurse($part->parts, $partNumber.'.', 0, false);
                }
                $this->attachments[] = array(
                    'type' => $part->type,
                    'subtype' => $part->subtype,
                    'filename' => '',
                    'data' => $msg,
                    'inline' => false,
                );
            }
            elseif(isset($part->parts)) {
                if($fullPrefix) {
                    $this->recurse($part->parts, $prefix.$index.'.');
                } else {
                    $this->recurse($part->parts, $prefix);
                }
            }
            elseif($part->type > 2) {
                if(isset($part->id)) {
                    $id = str_replace(array('<', '>'), '', $part->id);
                    $this->attachments[$id] = array(
                        'type' => $part->type,
                        'subtype' => $part->subtype,
                        'filename' => $this->getFilenameFromPart($part),
                        'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
                        'inline' => true,
                    );
                } else {
                    $this->attachments[] = array(
                        'type' => $part->type,
                        'subtype' => $part->subtype,
                        'filename' => $this->getFilenameFromPart($part),
                        'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
                        'inline' => false,
                    );
                }
            }

            $index++;

        }

    }

    function getPart($partNumber, $encoding) {

        $data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
        switch($encoding) {
            case 0: return $data; // 7BIT
            case 1: return $data; // 8BIT
            case 2: return $data; // BINARY
            case 3: return base64_decode($data); // BASE64
            case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case 5: return $data; // OTHER
        }
    }

    function getFilenameFromPart($part) {

        $filename = '';

        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if(!$filename && $part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $filename;

    }

    function get_mail_body($body_type = 'html')
    {
        $mail_body = '';
        if($body_type == 'html'){
            $this->fetch();
            preg_match_all('/src="cid:(.*)"/Uims', $this->bodyHTML, $matches);

            if(count($matches)) {
                $search = array();
                $replace = array();

                foreach($matches[1] as $match) {
                    if(isset($this->attachments[$match])){
                        $strExtension = strtolower($this->attachments[$match]['subtype']);
                        if(in_array($strExtension,$this->allowedExtensions)){
                            $strEncodedImage = base64_encode($this->attachments[$match]['data']);
                            $strEncodedImageSizeKb = $this->getBase64ImageSize($strEncodedImage);
                            if($strEncodedImageSizeKb<=1000){
                                $search[] = "src=\"cid:$match\"";
                                $replace[] = "src=data:image/".$strExtension.";base64,".$strEncodedImage;    
                            }
                        }
                    }
                }

                $this->bodyHTML = str_replace($search, $replace, $this->bodyHTML);
                $this->bodyHTML = preg_replace("/(<img src=\"cid:.*?)(\">)/", "", $this->bodyHTML); 

                $mail_body = $this->bodyHTML;
            }
        }else{
                $mail_body = $this->bodyPlain;
        }
        return $mail_body;
    }


    private function getBase64ImageSize($base64Image){ //return memory size in B, KB, MB
        try{
            $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            //$size_in_mb    = $size_in_kb / 1024;

            return $size_in_kb;
        }
        catch(Exception $e){
            return $e;
        }
    }
}