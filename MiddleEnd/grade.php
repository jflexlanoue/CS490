<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include("util.class.php");


if(isset($_POST["Question"])){
    $Question = nl2br($_POST['Question']);
    $Answer = nl2br($_POST['Answer']); 
    $StudentAnswer = nl2br($_POST['StudentAnswer']);
    
} else{
    
    $Question = "public class Vehicle

{

  public void Drive(int miles)

  {

    // throw not implemented exception

  }

}";
    
    $Answer ="public class Car extends Vehicle

{

  public void Drive(int miles)

  {

    System.out.println(miles + \" miles\");

  }

}";
    
    $StudentAnswer = "public class Car extends Vehicle

{

  public void Drive(int miles)

  {

    System.out.println(miles + \" miles\");

  }

}";
    
    
    
    
}

?>

<form action="" method="POST">
    
    <textarea type="text" name = "Question"></textarea>
    <textarea type="text" name = "Answer"></textarea>
    <textarea type="text" name = "StudentAnswer"></textarea>
    <input type="submit" value="submit">
    
</form>

<?php 

    
?>



<table style="width:100%">
    <tr>
        <td>Question</td>
        <td>Answer</td>
        <td>Student Answer</td>
        <td>Grade</td>
        <td>FeedBack</td>
    </tr>
    
    <tr>
        <td><pre><?php echo  $Question?></pre></td>
        <td><pre><?php echo  $Answer?></pre></td>
        <td><pre><?php echo  $StudentAnswer?></pre></td>

    </tr>

</table>
