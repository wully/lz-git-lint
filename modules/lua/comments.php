<?php
class lua_comments extends Lint{

    public function check_row($rs,$pre_rs,$post_rs){

        $rs = trim($rs);
        $post = trim($post_rs);
        if($this -> no == 1){
            if(stripos($rs,"--") !== 0 && stripos($post_rs,"--") !== 0 ){
                $info = "第一行或者第二行请开始写程序文件的注释说明";
                $this -> print_error($info);
                return false;
            }
        }

        if(stripos($post,'function') === 0 && (stripos($rs,'--') !== 0 && stripos($rs,"]]--") !==0 ) ){ 
            $info = "请在函数前一行写上函数功能注释";
            $this -> print_error($info);
            return false; 
        }


    }
    public function check_contents($contents){

    }


}



?>
