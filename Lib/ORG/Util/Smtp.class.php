<?php

/**
 * @version    $Id: smtp.class.php 74 2012-03-12 01:40:45Z qinjinpeng $
 */

class Smtp
{

    /* Public Variables */
    var $smtp_port;
    var $time_out;
    var $host_name;
    var $log_file;
    var $relay_host;
    var $debug;
    var $auth;
    var $user;
    var $pass;

    /* Private Variables */
    var $sock;
    /* Constractor */
	/*("smtp服务器,端口,true,转发邮箱账号,密码")*/
    function __construct($relay_host = "", $smtp_port = 25, $auth = false, $user = '', $pass = '')
    {
        $this->debug = FALSE;
        $this->smtp_port = $smtp_port;
        $this->relay_host = $relay_host;

        //is used in fsockopen()
        $this->time_out = 30;

        #
        $this->auth = $auth;//auth
        $this->user = $user;
        $this->pass = $pass;
        #

        //is used in HELO command
        $this->host_name = "localhost";
        $this->log_file = "";
        $this->sock = FALSE;
    }

    /* Main Function */
	/*(接收邮箱,发送邮箱,标题,内容,类型,cc,bcc,additional_headers)*/
    function SendMail($to, $from, $subject = "", $body = "", $mailtype = '', $cc = "", $bcc = "", $additional_headers = "")
    {
        $mail_from = $this->GetAddress($this->StripComment($from));
        $body = preg_replace("#(^|(\r\n))(\.)#", "\1.\3", $body);
        $header = "MIME-Version:1.0\r\n";
        if($mailtype=="HTML")
        {
            $header .= "Content-Type:text/html; charset=utf-8\r\n";
        }
        $header .= "To: ".$to."\r\n";
        if ($cc != "")
        {
            $header .= "Cc: ".$cc."\r\n";
        }

        $header .= "From: $from<".$from.">\r\n";
        $subject  = "=?utf-8?B?".base64_encode($subject)."?=";
        $header .= "Subject: ".$subject."\r\n";
        $header .= $additional_headers;
        $header .= "Date: ".date("r")."\r\n";
        $header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
        list($msec, $sec) = explode(" ", microtime());
        $header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
        $TO = explode(",", $this->StripComment($to));
        if ($cc != "")
        {
            $TO = array_merge($TO, explode(",", $this->StripComment($cc)));
        }
        if ($bcc != "")
        {
            $TO = array_merge($TO, explode(",", $this->StripComment($bcc)));
        }
        $sent = TRUE;
        foreach ($TO as $rcpt_to)
        {
            $rcpt_to = $this->GetAddress($rcpt_to);
            if (!$this->SmtpSockopen($rcpt_to))
            {
                $this->LogWrite("Error: Cannot send email to ".$rcpt_to."\n");
                $sent = FALSE;             
                continue;
            }
            if ($this->SmtpSend($this->host_name, $mail_from, $rcpt_to, $header, $body))
            {
                $this->LogWrite("E-mail has been sent to <".$rcpt_to.">\n");
            }
            else
            {
                $this->LogWrite("Error: Cannot send email to <".$rcpt_to.">\n");
                $sent = FALSE;              
            }
            fclose($this->sock);
            $this->LogWrite("Disconnected from remote host\n");
        }
        return $sent;
    }

    /* Private Functions */
    function SmtpSend($helo, $from, $to, $header, $body = "")
    {
        if (!$this->SmtpPutcmd("HELO", $helo))
        {
            return $this->SmtpError("sending HELO command");
        }

        #auth
        if($this->auth)
        {
            if (!$this->SmtpPutcmd("AUTH LOGIN", base64_encode($this->user)))
            {
                return $this->SmtpError("sending HELO command");
            }
            if (!$this->SmtpPutcmd("", base64_encode($this->pass)))
            {
                return $this->SmtpError("sending HELO command");

            }
        }

        #
        if (!$this->SmtpPutcmd("MAIL", "FROM:<".$from.">"))
        {
            return $this->SmtpError("sending MAIL FROM command");
        }
        if (!$this->SmtpPutcmd("RCPT", "TO:<".$to.">"))
        {
            return $this->SmtpError("sending RCPT TO command");
        }
        if (!$this->SmtpPutcmd("DATA"))
        {
            return $this->SmtpError("sending DATA command");
        }
        if (!$this->SmtpMessage($header, $body))
        {
            return $this->SmtpError("sending message");
        }
        if (!$this->SmtpEom())
        {
            return $this->SmtpError("sending <CR><LF>.<CR><LF> [EOM]");
        }
        if (!$this->SmtpPutcmd("QUIT"))
        {
            return $this->SmtpError("sending QUIT command");
        }
        return TRUE;
    }

    function SmtpSockopen($address)
    {
        if ($this->relay_host == "")
        {
            return $this->SmtpSockopenMx($address);
        }
        else
        {
            return $this->SmtpSockopenRelay();
        }
    }

    function SmtpSockopenRelay()
    {
        $this->LogWrite("Trying to ".$this->relay_host.":".$this->smtp_port."\n");
        $this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
        if (!($this->sock && $this->SmtpOk()))
        {
            $this->LogWrite("Error: Cannot connenct to relay host ".$this->relay_host."\n");
            $this->LogWrite("Error: ".$errstr." (".$errno.")\n");
            return FALSE;
        }
        $this->LogWrite("Connected to relay host ".$this->relay_host."\n");
        return TRUE;;
    }

    function SmtpSockopenMx($address)
    {
        $domain = preg_replace("#^.+@([^@]+)$#", "\1", $address);
        if (!@getmxrr($domain, $MXHOSTS))
        {
            $this->LogWrite("Error: Cannot resolve MX \"".$domain."\"\n");
            return FALSE;
        }
        foreach ($MXHOSTS as $host)
        {
            $this->LogWrite("Trying to ".$host.":".$this->smtp_port."\n");
            $this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
            if (!($this->sock && $this->SmtpOk()))
            {
                $this->LogWrite("Warning: Cannot connect to mx host ".$host."\n");
                $this->LogWrite("Error: ".$errstr." (".$errno.")\n");
                continue;
            }
            $this->LogWrite("Connected to mx host ".$host."\n");
            return TRUE;
        }
        $this->LogWrite("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
        return FALSE;
    }

    function SmtpMessage($header, $body)
    {
        fputs($this->sock, $header."\r\n".$body);
        $this->SmtpDebug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
        return TRUE;
    }

    function SmtpEom()
    {
        fputs($this->sock, "\r\n.\r\n");
        $this->SmtpDebug(". [EOM]\n");
        return $this->SmtpOk();
    }

    function SmtpOk()
    {
        $response = str_replace("\r\n", "", fgets($this->sock, 512));
        $this->SmtpDebug($response."\n");
        if (!preg_match("#^[23]#", $response))
        {
            fputs($this->sock, "QUIT\r\n");
            fgets($this->sock, 512);
            $this->LogWrite("Error: Remote host returned \"".$response."\"\n");
            return FALSE;
        }
        return TRUE;
    }

    function SmtpPutcmd($cmd, $arg = "")
    {
        if ($arg != "")
        {
            if($cmd=="")
            {
                $cmd = $arg;
            }
            else
            {
                $cmd = $cmd." ".$arg;
            }
        }
        fputs($this->sock, $cmd."\r\n");
        $this->SmtpDebug("> ".$cmd."\n");
        return $this->SmtpOk();
    }

    function SmtpError($string)
    {
        $this->LogWrite("Error: Error occurred while ".$string.".\n");
        return FALSE;
    }

    function LogWrite($message)
    {
        $this->SmtpDebug($message);
        if ($this->log_file == "")
        {
            return TRUE;
        }
        $message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
        if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a")))
        {
            $this->SmtpDebug("Warning: Cannot open log file \"".$this->log_file."\"\n");
            return FALSE;;
        }
        flock($fp, LOCK_EX);
        fputs($fp, $message);
        fclose($fp);
        return TRUE;
    }

    function StripComment($address)
    {
        $comment = "#\([^()]*\)#";
        while (preg_match($comment, $address))
        {
            $address = preg_replace($comment, "", $address);
        }
        return $address;
    }

    function GetAddress($address)
    {
        $address = preg_replace("#([ \t\r\n])+#", "", $address);
        $address = preg_replace("#^.*<(.+)>.*$#", "\1", $address);
        return $address;
    }

    function SmtpDebug($message)
    {
        if ($this->debug)
        {
            echo $message;
        }
    }
}

?>