<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Registration','Diary','Message','Conversation','Note','Payout'));
    }

    public function index() {
        return false;
        $this->encrypt_db();
    }

    private function encrypt_db() {

        $diaries = $this->Diary->get_all();
        foreach($diaries as $diary) {
            $this->Diary->update($diary->id, array(
                'title' => $diary->title,
                'text' => $diary->text,
                'comments' => $diary->comments
            ));
        }
        $messages = $this->Message->get_all();
        foreach($messages as $message) {
            $this->Message->update($message->id, array(
                'title' => $message->title,
                'text' => $message->text
            ));
        }
        $conversations = $this->Conversation->get_all();
        foreach($conversations as $conversation) {
            $this->Conversation->update($conversation->id, array(
                'excerpt' => $conversation->excerpt
            ));
        }
        $notes = $this->Note->get_all();
        foreach($notes as $note) {
            $this->Note->update($note->id, array(
                'text' => $note->text
            ));
        }
        $registrations = $this->Registration->get_all();
        foreach($registrations as $registration) {
            $this->Registration->update($registration->id, array(
                'data' => $registration->data
            ));
        }
    }

    private function log_echo($text) {
        echo $text."<br/>";
        log_message('info', $text);
    }
}