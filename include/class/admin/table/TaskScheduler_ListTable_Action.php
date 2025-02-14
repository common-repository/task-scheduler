<?php
/**
 * Handles actions sent to the form of the list table of Task Scheduler tasks. 
 *    
 * @package     Task Scheduler
 * @copyright   Copyright (c) 2014-2020, Michael Uno
 * @author      Michel Uno
 * @authorurl   http://michaeluno.jp
 * @since       1.0.0 
*/

if ( ! class_exists( 'WP_List_Table' ) ) { 
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class TaskScheduler_ListTable_Action extends TaskScheduler_ListTable_Base {
    
    /**
     * Defines the bulk actions.
     */ 
    public function get_bulk_actions() {
        
        $_aGET = TaskScheduler_Utility::getHTTPQueryGET() + array(
            'status' => '',
        );
        switch( $_aGET[ 'status' ] ) {
            
            default:
            case 'enabled':        
                return array(
                    'disable'       => __( 'Disable', 'task-scheduler' ),
                    'reset_status'  => __( 'Reset Status', 'task-scheduler' ),
                    'reset_counts'  => __( 'Reset Counts', 'task-scheduler' ),
                );
            
            case 'disabled':
                return array(
                    'enable'    => __( 'Enable', 'task-scheduler' ),
                    'delete'    => __( 'Delete', 'task-scheduler' ),
                );
                
            case 'routine':
            case 'thread':
                return array(
                    'delete'    => __( 'Delete', 'task-scheduler' ),
                );
                
        }
        
    }
    
    /**
     * Deals with the bulk actions.
     * 
     * Called from outside.
     */
    public function process_bulk_action() {
            
        // the key is defined as the singular slug and inserted in the input checkboxes of the cb column.
        if ( ! isset( $_REQUEST[ 'task_scheduler_task' ], $_REQUEST[ 'task_scheduler_nonce' ] ) ) { // sanitization unnecessary
            return; 
        }

        if ( ! wp_verify_nonce( $_REQUEST[ 'task_scheduler_nonce' ], 'task_scheduler_list_table_action' ) ) {   // sanitization unnecessary as just checking
            $this->setAdminNotice( __( 'The action has been already done or is invalid.', 'task-scheduler' ) );
            return;
        }

        $_iApplied     = 0;
        switch( strtolower( $this->current_action() ) ) {
            case 'enable':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sTaskPostID ) {   // sanitization done
                    $_oTask = TaskScheduler_Routine::getInstance( absint( $_sTaskPostID ) ); // sanitization done
                    $_oTask->enable();                            
                    $this->setAdminNotice( __( 'The task has been enabled.', 'task-scheduler' ), 'updated' );
                }
                break;            
            case 'disable':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sTaskPostID ) {      // sanitization done
                    $_iTaskPostID = absint( $_sTaskPostID );       // sanitization done
                    $_oTask = TaskScheduler_Routine::getInstance( $_iTaskPostID );
                    $_oTask->disable();
                    $this->___deleteRoutinesOfTask( $_iTaskPostID );
                    $this->setAdminNotice( __( 'The task has been disabled.', 'task-scheduler' ), 'updated' );                    
                }
                break;
            case 'delete':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sTaskPostID ) {      // sanitization done
                    
                    $_sKey = array_search( $_sTaskPostID, $this->aData );
                    if ( false !== $_sKey ) {
                        unset( $this->aData[ $_sKey ] );
                    }        
                    $_oTask = TaskScheduler_Routine::getInstance( absint( $_sTaskPostID ) );    // sanitization done
                    if ( is_object( $_oTask ) ) {    // sometimes the routine is already deleted by a different process
                        $_oTask->delete();
                    }
                    $this->setAdminNotice( __( 'The task has been deleted.', 'task-scheduler' ), 'updated' );
                }                
                break;
            case 'run':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sPostID ) {              // sanitization done
                    $_oTaskOrRoutine = TaskScheduler_Routine::getInstance( absint( $_sPostID ) );   // sanitization done
                    if ( ! ( $_oTaskOrRoutine instanceof TaskScheduler_Routine ) ) {
                        continue;
                    }
                    $_oTaskOrRoutine->start( microtime( true ) + ++$_iApplied, true );
                }
                if ( $_iApplied ) {
                    $this->setAdminNotice( __( 'The task has been called.', 'task-scheduler' ), 'updated' );
                }
                break;
            case 'reset_status':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sTaskPostID ) {      // sanitization done
                    $_oTask = TaskScheduler_Routine::getInstance( absint( $_sTaskPostID ) );    // sanitization done
                    $_oTask->resetStatus();
                    $_iApplied++;
                    $this->setAdminNotice( __( 'Reset the status.', 'task-scheduler' ), 'updated' );
                }                    
                break;    
            case 'reset_counts':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sTaskPostID ) {      // sanitization done
                    $_oTask = TaskScheduler_Routine::getInstance( absint( $_sTaskPostID ) );    // sanitization done
                    $_oTask->resetCounts();
                    $_iApplied++;
                    $this->setAdminNotice( __( 'Reset the counts.', 'task-scheduler' ), 'updated' );
                }                                
                break;
            case 'clone':
                foreach( ( array ) $_REQUEST[ 'task_scheduler_task' ] as $_sTaskPostID ) {  // sanitization done
                    $_oCloneTask = new TaskScheduler_CloneTask( absint( $_sTaskPostID ) );  // sanitization done
                    $_ioTask      = $_oCloneTask->perform();
                    if ( is_wp_error( $_ioTask ) ) {
                       $this->setAdminNotice( $_ioTask->get_error_message() );
                    } else {
                        $_iApplied++;
                    }
                }
                if ( $_iApplied ) { 
                    $this->setAdminNotice( __( 'Cloned a task.', 'task-scheduler' ), 'updated' );
                }
                break;
            default:
                break;    // do nothing.
                
        }

        $_sCurrentURL = remove_query_arg(
            array( 'action', 'task_scheduler_task' ),
            TaskScheduler_PluginUtility::getCurrentAdminURL()
        );
        exit( wp_redirect( $_sCurrentURL ) );

    }

        /**
         * Deletes routines that are awaiting.
         * This is called when the user disables a task. And routines that haven't started are deleted with this method.
         * @param integer $iTaskID
         * @since 1.6.3
         */
        private function ___deleteRoutinesOfTask( $iTaskID ) {
            $_oResults = TaskScheduler_RoutineUtility::find( array(
                'post_type'         => array(
                    TaskScheduler_Registry::$aPostTypes[ 'routine' ],
                ),
                'meta_query'        => array(
                    'relation'      => 'AND',    // or 'OR' can be specified
                    array(
                        'key'       => '_routine_status',
                        'value'     => array( 'ready', 'queued', 'awaiting' ),
                        'compare'   => 'IN',
                    ),
                ),
            ) );
            $_aRoutineIDs = $_oResults->posts;
            foreach( $_aRoutineIDs as $_iPostID ) {
                wp_delete_post( $_iPostID, true );    // true: force delete, false : trash
            }
        }

}