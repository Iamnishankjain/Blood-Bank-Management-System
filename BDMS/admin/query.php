<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
    #sidebar {
      position: relative;
      margin-top: -20px
    }

    #content {
      position: relative;
      margin-left: 210px
    }

    @media screen and (max-width: 600px) {
      #content {
        position: relative;
        margin-left: auto;
        margin-right: auto;
      }
    }

    #he {
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      padding: 3px 7px;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
      text-align: center; /* Corrected: align -> text-align */
    }
  </style>
</head>

<?php
include 'conn.php';
include 'session.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  ?>
  <body style="color:black">
    <div id="header">
      <?php include 'header.php'; ?>
    </div>
    <div id="sidebar">
      <?php $active = "query";
      include 'sidebar.php'; ?>
    </div>
    <div id="content">
      <div class="content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 lg-12 sm-12">
              <h1 class="page-title">User Query</h1>
            </div>
          </div>
          <hr>
          <script>
            function clickme() {
              if (confirm("Do you really Want to Read ?")) {
                document.getElementById("demo").innerHTML = "Pending";
                <?php
                // Use prepared statements to prevent SQL injection
                $que_id = mysqli_real_escape_string($conn, $_GET['id']);
                $sql1 = "UPDATE contact_query SET query_status='1' WHERE query_id=?";
                $stmt = mysqli_prepare($conn, $sql1);
                mysqli_stmt_bind_param($stmt, "i", $que_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                ?>
              }
            }
          </script>

          <?php
          include 'conn.php';

          $limit = 10;
          if (isset($_GET['page'])) {
            $page = $_GET['page'];
          } else {
            $page = 1;
          }
          $offset = ($page - 1) * $limit;
          $count = $offset + 1;
          $sql = "SELECT * FROM contact_query LIMIT ?, ?";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          if (mysqli_num_rows($result) > 0) {
          ?>
            <div class="table-responsive">
              <table class="table table-bordered" style="text-align:center">
                <thead style="text-align:center">
                  <th style="text-align:center">S.no</th>
                  <th style="text-align:center">Name</th>
                  <th style="text-align:center">Mobile Number</th>
                  <th style="text-align:center">Email</th>
                  <th style="text-align:center">Message</th>
                  <th style="text-align:center">Posting Date</th>
                  <th style="text-align:center">Status</th>
                  <th style="text-align:center">Action</th>
                </thead>
                <tbody>
                  <?php
                  while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                      <td><?php echo $count++; ?></td>
                      <td><?php echo htmlspecialchars($row['query_name']); ?></td>
                      <td><?php echo htmlspecialchars($row['query_mail']); ?></td>
                      <td><?php echo htmlspecialchars($row['query_number']); ?></td>
                      <td><?php echo htmlspecialchars($row['query_message']); ?></td>
                      <td><?php echo htmlspecialchars($row['query_date']); ?></td>
                      <?php if ($row['query_status'] == 1) { ?>
                        <td>read<br></td>
                      <?php } else { ?>
                        <td><a href="query.php" onclick="clickme()"><b id="demo">read</b></a><br></td>
                      <?php } ?>
                      <td id="he" style="width:100px">
                        <a style="background-color:aqua" href='delete_query.php?id=<?php echo $row['query_id']; ?>'> Delete </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php } ?>

          <div class="table-responsive" style="text-align:center;align:center">
            <?php
            $sql1 = "SELECT COUNT(*) as total FROM contact_query";
            $result1 = mysqli_query($conn, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            $total_records = $row1['total'];
            $total_page = ceil($total_records / $limit);

            echo '<ul class="pagination admin-pagination">';
            if ($page > 1) {
              echo '<li><a href="query.php?page=' . ($page - 1) . '">Prev</a></li>';
            }
            for ($i = 1; $i <= $total_page; $i++) {
              if ($i == $page) {
                $active = "active";
              } else {
                $active = "";
              }
              echo '<li class="' . $active . '"><a href="query.php?page=' . $i . '">' . $i . '</a></li>';
            }
            if ($total_page > $page) {
              echo '<li><a href="query.php?page=' . ($page + 1) . '">Next</a></li>';
            }

            echo '</ul>';
            ?>
          </div>
        </div>
      </div>
    </div>

  <?php
  } 
  else {
    echo '<div class="alert alert-danger"><b> Please Login First To Access Admin Portal.</b></div>';
    ?>
    <form method="post" name="" action="login.php" class="form-horizontal">
      <div class="form-group">
        <div class="col-sm-8 col-sm-offset-4" style="float:left">

          <button class="btn btn-primary" name="submit" type="submit">Go to Login Page</button>
        </div>
      </div>
    </form>
<?php }
 ?>

</body>
</html>

