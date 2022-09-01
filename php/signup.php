<?php
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        //User email valid or not
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){ //If email is valid
            //If email already exists in database 
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){ //If email already exists
                echo "$email - This email already exist!";
            }else{
                //User upload file or not
                if(isset($_FILES['image'])){ //If file is uploaded
                    $img_name = $_FILES['image']['name']; //Getting user uploaded image name
                    $tmp_name = $_FILES['image']['tmp_name']; //Using a temporary name to save/move file in our folder

                    //Getting image extension - .jpg .png .jpeg
                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode); //Getting the extension of a user uploaded image

                    $extensions = ['png', 'jpeg', 'jpg']; //Valid image Upload extensions stored in array
                    if(in_array($img_ext, $extensions) === true){ //If image match with any array extension
                        $time = time(); //Returning Current time
                                        //Renaming user uploaded image file with current time
                                        //Giving all image file a unique name
                        //Moving user uploaded image file to our Permanent folder
                        $new_img_name = $time.$img_name;
                        
                        if(move_uploaded_file($tmp_name, "images/".$new_img_name));{ //If user uploaded image move to our folder successfully
                            $status = "Active now"; //Changing user status once signed in
                            $random_id = rand(time(), 10000000); //Creating a random id for users

                            //Inserting all user data in a table
                            $sql2 = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                                VALUES ({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}')");
                            if($sql2){ // If these data is inserted
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                if(mysqli_num_rows($sql3) > 0){
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id']; //Using the session we sued in other php file "unique_id"
                                    echo "Success";
                                }
                            }else{
                                echo "Something went wrong!";
                            }
                        }

                    }else{
                        
                        echo "Please select an image file - .jpeg .jpg .png!";
                    }

                }else{
                    echo "Please select an image file";
                }
            }
        }else{
            echo "$email - This is not a valid email!";
        }
    }else{
        echo "All input field are required!";
    }
?>