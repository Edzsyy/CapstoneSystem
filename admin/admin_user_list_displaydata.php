<?php 
    include('../admin/assets/config/dbconn.php');


    if(isset($_POST['displaysend']))
    {
        $table = '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Number</th>
                            <th scope="col">Address</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>';

        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);
        $number = 1;
        

        while ($row = mysqli_fetch_assoc($result))
        {
            $id = $row['id'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $email = $row['email'];
            $phone = $row['phone'];
            $address = $row['address'];
            $table.='<tr>
                        <td scope="row">'.$number.'</td>
                        <td>'.$fname.'</td>
                        <td>'.$lname.'</td>
                        <td>'.$email.'</td>
                        <td>'.$phone.'</td>
                        <td>'.$address.'</td>
                        <td>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="btn btn-dark dropdown-item" onclick="getdetails('.$id.')"><i class="fa-sharp fa-solid fa-pen-to-square icon"></i> Update</button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="btn btn-danger dropdown-item" onclick="deleteuser('.$id.')"><i class="fa-solid fa-trash icon"></i> Delete</button></li>
                            </ul>
                            
                        </td>
                        </td>                       
                    </tr>';
                    $number++;
        }
    
        $table.='</table>';
        echo $table;
    }
?>

    

