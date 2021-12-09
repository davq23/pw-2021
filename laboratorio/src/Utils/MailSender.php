<?php

namespace Utils;

/**
 * Description of MailSender
 *
 * @author davido
 */
class MailSender
{
    private array $attatchments;
    private string $message;
    private $parameters;
    private string $subject;
    private string $to;

    public function addAttachment($attachment) {
        $this->attatchments[] = $attachment;
    }

    public function getAttatchments(): array {
        return $this->attatchments;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function getSubject(): string {
        return $this->subject;
    }

    public function getTo(): string {
        return $this->to;
    }

    public function setAttatchments(array $attatchments): void {
        $this->attatchments = $attatchments;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function setParameters($parameters): void {
        $this->parameters = $parameters;
    }

    public function setSubject(string $subject): void {
        $this->subject = $subject;
    }

    public function setTo(string $to): void {
        $this->to = $to;
    }

    public function send() {
        $from = LAB_EMAIL;
        $eol = PHP_EOL;
        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        $body = "--" . $separator . $eol;
        $body .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
        $body .= "This is a MIME encoded message." . $eol;

        // message
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
        $body .= $this->message . $eol;

        $counter = 1;

        // attachment
        foreach ($this->attatchments as $attachment) {
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"attachment_" . $counter . ".pdf\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol . $eol;
            $body .= chunk_split(base64_encode($attachment)) . $eol;
            $body .= "--" . $separator . "--";
            $counter++;
        }

        // main header
        $headers = "From: " . $from . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";

        mail($this->to, $this->subject, $body, $headers);
    }

}
