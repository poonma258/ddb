<?php
include("config.php");
$msg=$_SESSION["rollno"];
include("nav_bar.php");

if(!isset($_SESSION["rollno"]))
    header("location:index.php");
$rollno=$_SESSION["rollno"];
if ($rollno==2014130999)
    header("Location:admin_page.php");
$query="select * from conc_dtb where UID=$rollno";
$result=mysqli_query($db_var,$query) or die(mysql_error());
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
$status=$row["Status"];
//$exp_dt=strtotime($row["Expiry Date"]);
//$exp_dt=date("Y-m-d",$exp_dt);
if($status == "requested")
{
    $_SESSION["msgAwait"]="Your form has still not been reviewed by the personnel. Do come back to check on your status!";
    header("Location:await_results.php");
}
if($status == "locked")
{
    $query = "select Expiry_date from conc_dtb where UID='$rollno'";
    $result=mysqli_query($db_var,$query) or die(mysql_error());
    $object = $result->fetch_object();
    $date = date("d-m-Y",strtotime($object->Expiry_date));
    //$expDate =
    $_SESSION["msgAwait"]="The Form has been Approved, You can collect yor form from the office. Now Your Account will be locked until $date ";
    header("Location:await_results.php");
}

if(isset($_POST["Class"])&&isset($_POST["Period"])&&isset($_POST["Issue_date"]))
{
    $class = $_POST["Class"];
    $per = $_POST["Period"];
    $issue = $_POST["Issue_date"];
    $exp = strtotime($issue);
    $exp = strtotime(" +{$per} month",$exp);
    $exp = strtotime("-1 week", $exp);
    $exp = date('Y-m-d',$exp);
    $query = "UPDATE conc_dtb SET Class='$class',Period='$per', Issue_date='$issue',Expiry_date='$exp', Status='requested' WHERE UID='$rollno'";
    $result=mysqli_query($db_var,$query) or die(mysql_error());

    header("Location:student_home.php");
}
?>


    <div class="jumbotron">
        <h1>Welcome
        </h1>
        <h4>Enter details for Railway Pass</h4>
        <form role="form" method="POST" action="student_home.php">
            <div class="col-xs-3">
            <div class="form-group">
                <label class="control-label" ">Class</label>
                <select class="form-control" name="Class" required="required" placeholder="Class">
                    <option value="first">First</option>
                    <option value="sec">Second</option>
                </select>
            </div>
            </div>
            <br><br><br><br><br>
            <div class="col-xs-3">
            <div class="form-group">
                <label class="control-label" ">Period</label>
                <select class="form-control" name="Period" required="required" placeholder="Period">
                    <option value="1">Monthly</option>
                    <option value="3">Quarterly</option>
                </select>
            </div>
            </div>
            <br><br><br><br><br>
            <div class="col-xs-3">
            <div class="form-group">
                <label class="control-label" ">Date</label>
                <?php $curr = date("Y-m-d");
                $curr = strtotime($curr);
                $curr = strtotime(" +3 day",$curr);
                $curr = date('Y-m-d',$curr);
                ?>
                <input type="date" class="form-control" min="<?php echo date("Y-m-d"); ?>"max="<?php echo $curr; ?>" name="Issue_date" required="required" placeholder="Date You want Issue">
            </div>
            </div>
            <br><br><br><br><br>
            <button type="submit" class="btn btn-large btn-success">Submit</button>
        </form>
        <br><br><br><br><br><br><br><br>
        <footer class="footer">
            <p>&copy Sardar Patel Institute of Technology</p>
        </footer>
    </div>

<!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

