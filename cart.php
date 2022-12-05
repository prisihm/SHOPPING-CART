<?php
	

	require_once 'config/Config.php';
	$user_function = new Config;
	

	$counter = 1;
	

	$total = array();
	

	?>
	<!DOCTYPE html>
	<html>
	<head>
	    <!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	

	    <title>My Cart</title>
	    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
	        rel="stylesheet">
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

	    <link href="css/custome-style.css" type="text/css" rel="stylesheet">
	</head>
	

	<body>
	

	    <?php include_once('header.php'); ?>
	

	    <div class="container-fluid mt-5">
	        <div class="container">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="card">
	                        <div class="card-header">
	                           <b> My Cart Detail </b>
	                        </div>
	                        <div class="card-body">
	                            <div class="table-responsive-lg">
	                                <table class="table v-set">
	                                    <thead>
	                                        <tr>
	                                            <th scope="col">No</th>
	                                            <th scope="col">Product</th>
	                                            <th scope="col">Detail</th>
	                                            <th scope="col">Quantity</th>
	                                            <th scope="col">Price</th>
	                                            <th scope="col">Subtotal</th>
	                                            <th scope="col">Action</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <?php if($cart_data){ foreach($cart_data as $cart_key => $cart_value){ 
	                                            $qty = $_SESSION['product_qty_cart'][$cart_key];
	                                            $field_val['p_number'] = $cart_value;
	                                            $get_cart = $user_function->select_where_cart("products", $field_val);
	                                            $subtotal = $qty * $get_cart['p_amount'];
	                                            $total[] = $subtotal;
	                                        ?>
	                                        <tr>
	                                            <th scope="row"><?php echo $counter; $counter++; ?></th>
	                                            <td>
	                                                <img src="images/<?php echo $get_cart['p_image']; ?>" class="box-image-set-2">
	                                            </td>
	                                            <td><?php echo $get_cart['p_name']; ?></td>
	                                            <td><?php echo $qty; ?></td>
	                                            <td><?php echo $get_cart['p_amount']; ?></td>
	                                            <td><?php echo $subtotal; ?></td>
	                                            <td>
	                                                <button class="btn btn-sm btn-danger rm-val" data-dataval="<?php echo $cart_key; ?>">
	                                                    <span><i class="far fa-trash-alt"></i></span>
	                                                    <span>Remove</span>
	                                                </button>
	                                            </td>
	                                        </tr>
	                                        <?php }}else{ echo "<tr><td colspan='7'><h1 class='text-center' >Cart is Empty</h1></td></tr>"; } ?>
	                                    </tbody>
	                                    <tfoot>
	                                        <tr>
	                                            <td colspan="7"><b> Total Amount :: <?php echo @array_sum($total); ?> </b> </td>
	                                        </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	                        </div>
	

	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	

	    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

	

	    <script type="text/javascript">
	    
	    $(document).ready(function(){
	        $(document).on('click', 'button.rm-val', function(){
	            var rm_val = $(this).data('dataval');
	            if(rm_val == ''){
	                alert('Data Value Not Found');
	            }
	            else{
	                $.ajax({
	                    type: "POST",
	                    url: "ajax/cart-process.php",
	                    data: { 'rm_val' : rm_val },
	                    success: function (response) {
	                        var get_val = JSON.parse(response);
	                        if(get_val.status == 102){
	                            console.log(get_val.msg);
	                            location.reload();
	                        }
	                        else{
	                            console.log(get_val.msg);
	                        }
	                    }
	                });
	            }
	        });
	    });
	    
	    </script>
	

	</body>
	

	</html>
code for config



<?php
	

	session_start();
	

	class Config{
	

		private $DBHOST='localhost';
		private $DBUSER='root';
		private $DBPASS='';
		private $DBNAME='anonymous';
		public $con;
	

		public function __construct(){
			$this->con = mysqli_connect($this->DBHOST, $this->DBUSER, $this->DBPASS, $this->DBNAME);
			if(!$this->con){
				return false;
			}
		}
	

		public function htmlvalidation($form_data){
			$form_data = trim( stripslashes( htmlspecialchars( $form_data ) ) );
			$form_data = mysqli_real_escape_string($this->con, trim(strip_tags($form_data)));
			return $form_data;
		}
	

	  public function select_with_order($tblname, $condition, $order, $order_by='DESC', $op='AND'){
	

			$field_op = "";
			foreach ($condition as $q_key => $q_value) {
				$field_op = $field_op."$q_key='$q_value' $op ";
			}
			$field_op = rtrim($field_op,"$op ");
	

			$select = "SELECT * FROM $tblname WHERE $field_op ORDER BY $order $order_by";
			$select_fire = mysqli_query($this->con, $select);
			if(mysqli_num_rows($select_fire) > 0){
				$select_fetch = mysqli_fetch_all($select_fire, MYSQLI_ASSOC);
				if($select_fetch){
					return $select_fetch;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
	

		public function select_where_cart($tblname, $condition, $op="AND"){
	

			$condition_data = "";
			foreach ($condition as $c_key => $c_value) {
				$condition_data = $condition_data."$c_key='$c_value' $op ";
			}
			$condition_data = rtrim($condition_data,"$op ");
	

			$select = "SELECT * FROM $tblname WHERE $condition_data";
			$select_fire = mysqli_query($this->con, $select);
			if(mysqli_num_rows($select_fire) > 0){
				$select_fetch = mysqli_fetch_array($select_fire);
				if($select_fetch){
					return $select_fetch;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}	
	

		}
	

	}
	?>




