## Good Code

You will find **good, fine and strong codes** in recently developed pages. One example is Apptease.
I think **Apptease** contains some really nice codes as compared to older pages. Also the **new homepage,
appcetra, password recovery application, sign-up application, new profile page** contain some extremely
useful piece of codes. Do review them, and **comment on commits** if you want to discuss them. 
 
Apptease is present in following directory structure :
 * src
  * home
     * apps
         * apptease
             * ver10
           
## Bad code 

You may find some **unreasonably-resource-consuming, inefficient** codes on older pages like indexold.php, indexold2.php 
and some others. Sorry for that! That's because, those were written when this project was inititated and I had never
written in PHP before Kornfl-ex. *But, you can improve such codes if you find some, beacuse these source
codes are all yours now.*

## Caution

These codes are **not copy-paste-run kind** of codes. You will have to setup PHP with some Web-server and some database to
be able to run php files on your local machine. The best option is `Apache` as your local web server and `MySql` as
database server. But you are free to choose any other. After, that you will have to change the **connection string** in each
page. It will be like :

       $con = mysql_connect("<hostname>","<db-username>","<db-password>");
       if(!$con) {
             echo "Couldn't connect to database";
             exit;
       }
       mysql_select_db("<db-name>", $con);

I have used `mysql` api to connect to database in my codes, which is quite out of fashion now. You can use newer APIs like
`ext/mysqli` or `PDO_MySql`. These are the recommended APIs by [php.net](http://php.net).

### Furthur help

I will be soon posting tutorials for `PHP` and `Javascript` and will share, whatever (though little) I have learnt about
these two wonderful languages. For now, a typical `connection string` in `ext/mysqli` API will look like :
     
        $mysqli = new mysqli("example.server.com", "username", "password", "databasename");
        if($mysql->connect_errno) {
              echo "error ocurred".$mysqli->connect_error;
         }
        $res = $mysqli->query("SELECT 'mysqli api also provides procedural interface' AS mysqliBenefit FROM dual");
        $row = $res->fetch_assoc();
        echo $row['mysqliBenefit'];


>Copyright 2012 Krishna Murti