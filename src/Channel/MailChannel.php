<?php

declare(strict_types=1);

namespace MessageNotify\Channel;

use MessageNotify\Template\AbstractTemplate;
use PHPMailer\PHPMailer\PHPMailer;

class MailChannel extends AbstractChannel
{
    public function send(AbstractTemplate $template)
    {
        // TODO: Implement send() method.
        var_dump('配置文件：');
        var_dump($template);
        $config = $this->getQuery($template->getPipeline());
        var_dump($config);
        $mail_to_email_str = $template->getTo();
        $issent            = false;
        try {
            $mail_to_email_array = explode(',', $mail_to_email_str);
            foreach ($mail_to_email_array as $mail_to_email) {
                //gmail 发送需要服务器翻墙。先用163测试吧
                //Server settings
                //            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                //            $mail->isSMTP();                                            // Send using SMTP
                //            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                //            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                //            $mail->Username   = 'charlie.lasfit@gmail.com';                     // SMTP username
                //            $mail->Password   = 'Millsaps6168$';                               // SMTP password
                //            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                //            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                /*163 来发，是下面的配置*/
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->CharSet = "UTF-8";
                // Send using SMTP
                //            $mail->Host       = 'smtp.163.com';                    // Set the SMTP server to send through
                //            $mail->Host = 'ssl://smtp.163.com'; # 阿里云线上使用ssl加密方式
                //            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                //            $mail->Username   = 'ganjian606@163.com';                     // SMTP username
                //            $mail->Password   = 'WBHUIUHLNAXRBFGH';                               // SMTP password
                ////            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                ////            $mail->Port       = 25;
                //            $mail->Port = 465; # ssl方式 用465端口
                //            $mail->setFrom('ganjian606@163.com', '唯高自研信息系统');//这个邮箱必须和设置SMTP邮箱一致*/
                /*阿里云默认封了25端口，需要把端口号改成587*/
                /*下面用阿里云的企业邮箱来发送*/
                $mail->Host     = $config['dns'];
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                //            $mail->Username   = 'kim@lasfit.com';                     // SMTP username
                //            $mail->Password   = '@vY4fn3eNxn8hr22';
                $mail->Username = $config['from'];                     // SMTP username
                $mail->Password = $config['password'];

                //            $mail->Port = 587;
                //            $mail->Port = 25;
                $mail->Port = $config['port'];
                //Recipients
                $mail->addCustomHeader('Content-type: text/html; ');
                $mail->setFrom($config['from'], '唯高自研信息系统');
                $mail->addAddress($mail_to_email);     // Add a recipient
                if (!empty($acc)) $mail->addBCC($acc);//密送
                if (!empty($replay_to)) $mail->addReplyTo($replay_to, '唯高自研信息系统 快速回复');//点击快速回复到这个邮箱里


                // Attachments 附件，路径如下
                //D:\vigo-erp\public\upload\vg_15574.pdf
                // dump($att);
                if (!empty($att)) {
                    $mail->addAttachment($att, '附件');         // Add attachments
                    //                $mail->addAttachment( '../public/upload/vg_15597.pdf', 'new.pdf' );    // Optional name
                }

                var_dump("邮件内容:");
                var_dump($template->getMailBody());
                $arr=get_object_vars($template);
                var_dump($arr);
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $template->getTitle();//主题
                $mail->Body    = $template->getMailBody();//内容
                $mail->AltBody = $template->getMailBody();

                $mail->send();
                $issent = true;
            }


            //echo 'Message has been sent';
        } catch (Exception $e) {
            var_dump($e->getMessage());
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $issent = false;
        }
        unset($mail);
        return $issent;
    }
    private function getQuery(string $pipeline): array{
        $config = $this->getConfig();
        $config = $config['pipeline'][$pipeline] ?? $config['pipeline'][$config['default']];
//        var_dump($config);
        return $config;
    }
}
