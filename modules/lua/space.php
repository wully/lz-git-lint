<?php
class lua_space extends Lint{
    public function check_row($rs,$pre_rs,$post_rs){
            if(preg_match("/[ \t]\n$/mis",$rs)){
                $info = "结尾有空格";
                $this -> print_error($info);

                return false;
            }
            return true;
    }
    public function check_contents($contents){

    }


}



?>
