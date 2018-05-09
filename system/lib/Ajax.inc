<?php
namespace sys\lib;
/**
 * Class Ajax
 * @package sys\lib
 */
class Ajax {
    /**
     * @param $original
     * @param $replace
     * @param $form
     * @return string
     */
    public function replace($original, $replace, $form) {
        return '<script type="text/javascript">
                $(document).ready(function(){
                    $("'.$original.'").click(function(){
                            var htmlobj = $.Ajax({
                            type:"POST",url:"'.$form.'",dataType:"json",async:false,
                        });
                        $("'.$replace.'").html(htmlobj.responseText);
                    });
                });
                </script>';
    }
}