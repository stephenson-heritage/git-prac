<?php


// For static (class)
// User::
// ! User->
class User {


    static function view($logged_in, $create_account, $user_data) {
        if($logged_in) {
            echo "<div>logged in as ".$user_data["username"];
            echo ' <a href="?logout">Logout</a>';
            echo "</div>";
        } else {
            if($create_account) {
                echo "<h1>Create new Account</h1>";
                echo '<a href="?">Login</a>';
            } else {
                echo "<h1>Login</h1>";
                echo '<a href="?ca">Create Account</a>';
            }
    ?>
        <form action="<?php echo $create_account ? '?ca' : '?' ?>" method="POST" enctype="application/x-www-form-urlencoded">
            <input type="text" name="username" />
            <input type="password" name="password" />
            <input type="submit" /> 
        </form>
    <?php
        }
    }

    // loggedin, data, create
    static function login($db) {
        $create_account = false;
        if(isset($_GET["ca"])) {
            $create_account = true;  
        }

        if(isset($_POST["username"]) && isset($_POST["password"])) {
            if($create_account) {
                // trying to make account
                User::addUser($db);
                return ["loggedin"=>false,
                "create"=>true
             ];        
            } else {
                // trying to log in via a form
                return ["data"=>User::loginWithForm($db),
                "loggedin"=>true,
                "create"=>false
             ];        
            }
        } elseif(isset($_COOKIE['user']) && isset($_COOKIE['h'])) {
            return ["data"=>User::loginWithCookies($db),
                "loggedin"=>true,
                "create"=>false];   
        }

        return ["loggedin"=>false,
                "create"=>$create_account];
    }

    static function logout($db, $user) {

        $cookieHash = "loggedout";
        $q = "UPDATE `user` SET `cookieHash` = ? WHERE username = ?";
        $ps = $db->prepare($q);

        $ps->execute([$cookieHash,$user]);

        setcookie("user","",1);
        setcookie("h","",1);
        return ["loggedin"=>false,"create"=>false];
    }

    static function addUser($db) {
        $user = $_POST["username"];
        $pass = $_POST["password"];
        $passHash = sha1($pass);
        $cookieHash = sha1($passHash);

        $q = 'INSERT INTO `user` (`username`, `passHash`, `cookieHash`) VALUES (:user, :passHash, :cookieHash);';
        $ps = $db->prepare($q);
        try{
            $ps->execute(["passHash"=>$passHash, "cookieHash"=>$cookieHash,"user"=>$user]);
        } catch (PDOException $err) {
            echo "err";
        }
    }
    // see if they exist in db and if hash of password is the same as hash
    static function loginWithForm($db) {
        $user = $_POST["username"];
        $pass = $_POST["password"];
        $passHash = sha1($pass);

        $q = 'SELECT `userID`,`username`,`passHash`,`cookieHash` FROM `user` WHERE username = :user AND passHash = :passHash';
        $ps = $db->prepare($q);

        $ps->execute(["user"=>$user,"passHash"=>$passHash]);


        if($ps->rowCount() == 1) {
            $user_data = $ps->fetch();

            // logged in successfully
            $cookieHash = sha1(time().$passHash);
            $q = "UPDATE `user` SET `cookieHash` = ? WHERE username = ?";
            $ps = $db->prepare($q);

            $ps->execute([$cookieHash,$user]);


            setcookie("user",$user,time()+60*60*24*7);
            setcookie("h",$cookieHash,time()+60*60*24*7);    
            
            return $user_data;
        } 
    }

    static function loginWithCookies($db) {

        $user = $_COOKIE['user'];
        $cookieHash = $_COOKIE['h'];

        $q = 'SELECT `userID`,`username`,`passHash`,`cookieHash` FROM `user` WHERE username = :user AND cookieHash = :cookieHash';
        $ps = $db->prepare($q);

        $ps->execute(["user"=>$user,"cookieHash"=>$cookieHash]);

        if($ps->rowCount() == 1) {
            $user_data = $ps->fetch();

            // logged in successfully
            $cookieHash = sha1(time().$user_data['passHash']);
            $q = "UPDATE `user` SET `cookieHash` = ? WHERE username = ?";
            $ps = $db->prepare($q);

            $ps->execute([$cookieHash,$user]);


            setcookie("user",$user,time()+60*60*24*7);
            setcookie("h",$cookieHash,time()+60*60*24*7); 
            
            return $user_data;
        }
    }
}

?>