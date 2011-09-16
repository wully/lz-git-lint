<?php
class lua_test extends Lint{
    public function check_row($rs,$pre_rs,$post_rs){
        if(preg_match("#local[ \t][ \t]*([^=]*)[ \t]*=#mis",$rs,$match)){
                $word = $match[1];
                if(!preg_match("#[a-zA-Z0-9]*#",$word)){
                    $info = "变量：$word 不合法";
                    $this -> print_error($info);
                    return false;
                }
            }
            return true;
    }
    public function check_contents($contents){

    }


}



?>
