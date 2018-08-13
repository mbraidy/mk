<?php include realpath(dirname(__DIR__)."/layout/top.php");?>

<div class="container">
    <h1>Item Management System:</h1>
    <form id="updateprofile" action="/item/update" method="POST" >
    	<div class="adminHeader">
        	<h3>Edit / Add:</h3>
    	  	<div>
                <button type="button" id="add_new_item">Save</button>
            </div>
		</div>
		<br/>
    	<div class="row">

			<label for="name" class="col-sm-4">Username<input placeholder="Item name *" id="name" name="name" value="" required/>
    		</label>
     		<label for="alt" class="col-sm-4">Alternative name
    			<input placeholder="Alternative name *" id="alt" name="alt" value="" required/>
    		</label>
    		<label for="title" class="col-sm-4">Title
    			<input placeholder="Title *" id="title" name="title" value="" required/>
    		</label>
    	</div>
     	<div class="row">
    		<label for="price" class="col-sm-3">Price
    			<input placeholder="Price *" id="price" name="price" value="" required/>
    		</label>
     		<label for="available" class="col-sm-3">Available
    			<input placeholder="Available *" id="available" name="available" value="" required/>
    		</label>
    		<div class="col-sm-2">
    			<h5><b>Active</b></h5>
    		    <label for="active" class="switch">
                	<input id="active" name="active" type="checkbox" class="checkboxed" id="active" checked>
                 	<span class="slider round"></span>
         		</label>
         	</div>
    		<label for="description" class="col-sm-4">Description
    			<input placeholder="Description" id="description" name="description" value=""/>
    		</label>
    	</div>
     	<div class="row" id="get_image">
     		<div class="col-sm-4">
    			<input type='file' id="file_select" name="picture" required/>
    		</div>
        	<div class="col-sm-8">
        		<img id="image_holder" src="/assets/images/picture-frame.png" alt="Picture to choose"/>
    		</div>
    	</div>
	</form>
</div>
<div class="container">
	<div class="alert alert-success" role="alert" id="message-success" hidden>
        <h4 class="alert-heading">Updating</h4>
        <i class="fa fa-smile-wink fa-3x text-success">
        </i><p>Item saved successfully!</p>
    </div>
	<div class="alert alert-danger" role="alert" id="message-failure" hidden>
         <h4 class="alert-heading">Updating</h4>
         <i class="fa fa-meh-rolling-eyes fa-3x text-danger"></i>
         <p>Failed! Item not saved!</p>
         <p id="error-explanation"></p>
    </div>
    <table id="admin-items" class="table table-responsive table-striped table-bordered table-hover">
    	<thead class="thead-dark">
    		<tr>
    			<th class="col-md-2">Item</th>
       			<th class="col-md-1">alt</th>
       			<th class="col-md-1">Title</th>
       			<th class="col-md-2">Picture</th>
       			<th class="col-md-1">Price</th>
       			<th class="col-md-2">Description</th>
      			<th class="col-md-1">Available</th>
      			<th class="col-md-1">Active</th>
     			<th class="col-md-1">Delete</th>
    		</tr>
    	</thead>
    	<tbody id="items_body">
    		<?php foreach ($items as $item) { ?>
		      <tr <?= ($item['active'])?"":"class='danger'"?> id="row__<?= $item['id']?>">
                 <td><input disabled class="editable" id="name__<?= $item['id']?>" value="<?= $item['name']?>"></td>
                 <td><input disabled class="editable" id="alt__<?= $item['id']?>" value="<?= $item['alt']?>"></td>
                 <td><input disabled class="editable" id="title__<?= $item['id']?>" value="<?= $item['title']?>"></td>
                 <td><input disabled class="editable" id="picture__<?= $item['id']?>" value="<?= $item['picture']?>"></td>
                 <td><?= $item['currency']?><input disabled class="editable" id="price__<?= $item['id']?>" value=" <?= $item['price']?>"></td>
                 <td><input disabled class="editable" id="description__<?= $item['id']?>" value="<?= $item['description']?>"></td>
                 <td><input disabled class="editable" id="available__<?= $item['id']?>" value="<?= $item['available']?>"></td>
                 <td>
                 	<label class="switch">
                     	<input type="checkbox" class="checkboxed" id="active__<?= $item['id']?>"
                     		   <?=($item['active'])?"checked":""?> >
                     	<span class="slider round"></span>
                 	</label>
                 </td>
                 <td>
                  	<i class="fa fa-trash-o fa-2x delete_row" id="delete__<?= $item['id']?>"></i>
                 </td>
		      </tr>
    		<?php } ?>
    	</tbody>
    </table>
</div>
<?php include realpath(dirname(__DIR__)."/layout/bottom.php");?>
