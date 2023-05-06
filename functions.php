<?php 

function seed(){
    $students = array(
        array(
            'id'    => 1,
            'fname' => 'Mohammad',
            'lname' => 'Shobuj',
            'email' => 'shobuj@shobuj.com',
            'roll'  => 11,
        ),
        array(
            'id'    => 2,
            'fname' => 'Shojib',
            'lname' => 'Khan',
            'email' => 'shojib@khan.com',
            'roll'  => 15,
        ),
        array(
            'id'    => 3,
            'fname' => 'Rupantor',
            'lname' => 'Khan',
            'email' => 'rupantor@khan.com',
            'roll'  => 2,
        ),
        array(
            'id'    => 4,
            'fname' => 'Tahmid',
            'lname' => 'Islam',
            'email' => 'tahmid@khan.com',
            'roll'  => 3,
        ),
        array(
            'id'    => 5,
            'fname' => 'Sheuly',
            'lname' => 'Akter',
            'email' => 'sheuly@khan.com',
            'roll'  => 10,
        ),
    );
    $serialized_data = serialize($students);
    file_put_contents(FILE_NAME, $serialized_data, LOCK_EX);
}

function all_students_display(){
    $unserialized_data = file_get_contents(FILE_NAME);
    $students = unserialize($unserialized_data);

    ?>
    <table class="table">
        <tr>
            <th>Roll</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php foreach( $students as $student ) : ?>
            <tr>
                <td><?php printf("%s", $student['roll']); ?></td>
                <td><?php printf("%s %s", $student['fname'], $student['lname']); ?></td>
                <td><?php printf("%s", $student['email']); ?></td>
                <td><?php printf("<a href='index.php?task=edit&id=%s'>Edit</a> | <a href='index.php?task=delete&id=%s'>delete</a>", $student['id'], $student['id']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php
}


function add_new_student($roll, $fname, $lname, $email){
    $found = false;
    $unserialized_data = file_get_contents(FILE_NAME);
    $allstudents = unserialize($unserialized_data);

    foreach($allstudents as $singlestudent){
        if($singlestudent['roll'] == $roll ){
            $found = true;
            break;
        }
    }

    if(!$found){
    
    $new_id = getNewId($allstudents);
    $student = array(
        'id'    => $new_id,
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'roll'  => $roll,
    );

    array_push($allstudents, $student);

    $serialized_data = serialize($allstudents);
    file_put_contents(FILE_NAME, $serialized_data, LOCK_EX);

    return true;
}
return false;

}


function get_and_update_student($id){
    $unserialized_data = file_get_contents(FILE_NAME);
    $students = unserialize($unserialized_data);

    foreach($students as $student){
        if($student['id'] == $id ){
            return $student;
        }
    }
    return false;
}


function update_student($id, $fname, $lname, $email){
    $unserialized_data = file_get_contents(FILE_NAME);
    $students = unserialize($unserialized_data);

    $students[$id-1]['fname'] = $fname;
    $students[$id-1]['lname'] = $lname;
    $students[$id-1]['email'] = $email;
    //$students[$id-1]['roll'] = $roll;

    $serialized_data = serialize($students);
    file_put_contents(FILE_NAME, $serialized_data, LOCK_EX);
}


function delete_student($id){
    $unserialized_data = file_get_contents(FILE_NAME);
    $students = unserialize($unserialized_data);

    unset($students[$id-1]);

    $serialized_data = serialize($students);
    file_put_contents(FILE_NAME, $serialized_data, LOCK_EX);

}

function getNewId($allstudents){
    $max_id = max(array_column($allstudents, 'id'));
    return $max_id+1;
}