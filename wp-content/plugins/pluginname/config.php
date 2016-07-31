<?php
/**
 * Created by teleioolab.
 * User: Sudip
 * Date: 17/03/2016
 * Time: 10:46 AM
 */

/**
 * routes format :
'routes' => array(

    'common' => array(


    ),
    'admin' => array(

        'action' => array(

            'admin_menu' => array(

                  array( 'controller' => 'Subscriber', 'action' => 'op_add_submenu', 'accepted_args'=>0,'priority'=>10, 'condition'=>function(){

                    return true|false;
                  } ),


            ),
        ),
        'filter' => array(


    ),
    'front' => array(

    )
)

*/

return array(

    'tasks' => array(

        'common' => array(



        ),
        'admin' => array(




        ),
        'front' => array(





        )
    )

);