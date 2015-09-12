<?php

class Response_HTML extends Response {

    public function __construct($template_path, $data, $status=200) {
        $this->template_path = $template_path;
        $this->data = $data;
        $this->status = $status;
    }

    public function getBody() {
        ob_start();
        extract($this->data);
        require Config::get('TEMPLATE_DIR').'/'.$this->template_path;
        return ob_get_clean();
    }
}
