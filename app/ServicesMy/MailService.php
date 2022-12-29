<?php

namespace App\ServicesMy;

use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    
    public function dotestMail($sub,$var,$sentto,$data,$emailcc)
    {

             $mail = new PHPMailer(true);     // Passing `true` enables exceptions
         
            try {

                // dd($emailcc);
             
                // Email server settings
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';             //  smtp host
                $mail->SMTPAuth = true;
                $mail->Username = 'noreply.esales@tatasteelmining.com';   //  sender 
                $mail->Password = '#)@00P!wap0c#%';       // sender password
                $mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
                $mail->Port = 587;                          // port - 587/465

                $mail->setFrom('noreply.esales@tatasteelmining.com', 'SenderName');
                $mail->addAddress($sentto);
                foreach ($emailcc as $key => $value) {
                    
                  $mail->addCC($value);
                }


                $mail->isHTML(true);                

                $mail->Subject = $sub;
                $mail->Body    = view($var, ['data' => $data])->render();;



                if( !$mail->send() ) {
                    return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
                }
                
                else {
                    return back()->with("success", "Email has been sent.");
                }

            } catch (Exception $e) {
                 return back()->with('error','Message could not be sent.');
            }
    }

}
