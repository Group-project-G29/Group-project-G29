<?php
 
 namespace app\core;
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 class Email{
    public string $sendemail;
    public string $subject;
    public string $body;
    public string $altbody='';
    
    public function sendOTP($otp,$email){
        $this->sendemail=$email;
        $this->subject="Change Passowrd";
        $this->body="
            <section>
                <h2> Change password</h2>
                <h3>Click <a href='http://localhost/ctest/otp?sec=".$otp."'>here</a>
            </section>
        
        ";
        $this->sendEmail();
    }

    public function sendEmail(){
        $mail = new PHPMailer(true);
            $mail->SMTPDebug = 2;                                      
            $mail->isSMTP();                                           
            $mail->Host       = 'smtp.gmail.com;';                   
            $mail->SMTPAuth   = true;                            
            $mail->Username   = 'anspaughcare@gmail.com';                
            $mail->Password   = 'bhdzvkveyalidrak';                       
            $mail->SMTPSecure = 'ssl';                             
            $mail->Port       = 465; 
        
            $mail->setFrom('anspaughcare@gmail.com', 'AnspughCare Channeling Center');          
            $mail->addAddress($this->sendemail);
            $mail->isHTML(true);                                 
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;
            $mail->AltBody = $this->altbody;
            $mail->send();
            
            
    }
}
 
?>