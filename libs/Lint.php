<?php
abstract class Lint {
    protected $path = "";
    protected $no = "";
    protected $show = "NOTICE";
    protected $handle;
    protected $offset;
    protected $libs_dir;
 
    public function init($path,$no,$show,$handle,$offset,$libs_dir){
        $this -> path = $path;
        $this -> no = $no;
        $this -> show = $show;
        $this -> handle = $handle;
        $this -> offset = $offset;
        $this -> libs_dir = $libs_dir;
    }

    protected function print_error($info){
        if(!empty($this -> show)){
            echo "\n### " . $this -> show . " ###" . "\n";
        }
        if(!empty($this -> path)){
            echo "path : " . $this -> path . "\n";
        }
        if(!empty($this -> no)){
            echo "no : " . $this -> no . "\n";
        }
        echo "info : " . $info . "\n";
        echo "\n";

}


    abstract function check_row($rs,$pre_rs,$post_rs);
    abstract function check_contents($contents);
    public function after_run(){
        fseek($this->handle,$this->offset);

    }
}

?>
