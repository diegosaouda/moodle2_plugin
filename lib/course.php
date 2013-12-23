<?php



function students_by_course($role_id, $context_in, $group_id = 0, $orderBy = "IFNULL(g.name,'W'),"){
    global $DB;
    
    
    if($group_id){
      $where .= ' and g.id = ' . $group_id . ' ';  
    }
    
    $sql = "select u.id
                    , u.firstname
                    , u.lastname
                    , u.email
                    , g.name group_name
            from {role_assignments} ra 
                inner join mdl_user u on (
                    u.id = ra.userid
                )
                left join {groups_members} gm on (
                    u.id = gm.userid	
                )
                left join {groups} g on (
                    g.id = gm.groupid	
                )
            where ra.roleid = {$role_id}
              and contextid {$context_in}
                
                {$where}
                
            order by {$orderBy} u.firstname, u.lastname";
    
    return $DB->get_records_sql($sql);    
}


