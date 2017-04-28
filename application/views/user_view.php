<div style="height: calc( 100vh - 110px );">
	<div class="container1" style="float: left;width:250px;margin-right:10px;">
		<input type="text" id="key_word" name="key_word" class="form-control" style="width:100%" value="" placeholder="Search ...">
		<div id="jstree" style="overflow:auto;height:calc( 100vh - 200px );">
		<ul>
			<li id="root" data-jstree='{ "opened" : "true","icon":"fa fa-users"}'>Users<ul><?php
				for($i=0;$i<count($users);$i++)
					echo "<li id='".$users[$i]['id']."' data-jstree='{\"icon\" : \"fa fa-user\" ,\"type\" : \"".$users[$i]['role']."\"}'>".$users[$i]['user_name'];					
				?></ul></li>
		</ul>
		</div>
	</div>
	<div class="container1" style="width:calc( 100% - 260px );overflow:auto;" id="content"></div>	
</div>

  <div class="modal fade" id="confirm_dialog" role="dialog">
    <div class="modal-dialog">
    <input type='hidden' id="user" name='user'>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete User</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this user ?</p>
        </div>
        <div class="modal-footer">
			<button type="button" class="btn btn-danger pull-left" id="confirm">Confirm</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<script>
$(function () {
	$.jstree.defaults.contextmenu.select_node = false;
	$("#jstree").jstree({
		"plugins" : [ "contextmenu","search","themes","state","unique","types"],
		"state" : {
			"key" : "users"
		},
		"core" : {
			"multiple" : false,
			"check_callback" : function(operation, node, node_parent, node_position, more) {
					return true;
				}
		},
		"types" : {
			"U" : {
			},
			"A" : {
			}
		},
		"themes" : { "stripes" : true },
		"contextmenu":{
			"items": function($node) {
				var tree = $("#jstree").jstree(true);
				var items ;
				if($node.parents.length == 1)
				{
					items = {
						"Create": {
							"separator_before": false,
							"separator_after": false,
							"label": "Create",
							"shortcut": 78,
							"shortcut_label": "NEW",
							"icon": "./images/create.png",
							"action": function (obj) {
								$node = tree.create_node($node);
							}
						}
					}
				}
				if($node.parents.length == 2)
				{
					items = {
						"Rename": {
							"separator_before": false,
							"separator_after": false,
							"label": "Rename",
							"shortcut": 113,
							"shortcut_label": "F2",
							"icon": "./images/edit.png",
							"action": function (obj) {
								tree.edit($node);
							}
						},       
						"Remove": {
							"separator_before": false,
							"separator_after": false,
							"label": "Remove",
							"icon": "./images/delete.png",
							"shortcut": 46,
							"shortcut_label": "DEL",
							"action": function (obj) {
								$('#user').val($node.id);
								$('#confirm_dialog').modal('show');
							}
						},
						"Make_Admin": {
							"separator_before": false,
							"separator_after": false,
							"label": "Make Admin",
							"icon": "./images/make_admin.png",
							"action": function (obj) {
								$.ajax({
								type: "POST",
								url: base_url+"user/make_admin", 
								data: {id:$node.id},
								dataType: "text",  
								cache:false,
								success: 
									function(data){
										tree.set_type($node,"A");
									}
								});
							}
						},
						"Remove_Admin": {
							"separator_before": false,
							"separator_after": false,
							"label": "Remove Admin",
							"icon": "./images/remove_admin.png",
							"action": function (obj) { 
								$.ajax({
								type: "POST",
								url: base_url+"user/remove_admin", 
								data: {id:$node.id},
								dataType: "text",  
								cache:false,
								success: 
									function(data){
										tree.set_type($node,"U");
									}
								});
							}
						}
					}
					if(tree.get_type($node) == "A")
						delete items.Make_Admin;
					else
						delete items.Remove_Admin;
				}
				return items;
			}
		}
	}).bind("create_node.jstree", function (e, data) {
		var node = $.extend(true, {}, data.node);
		$.ajax({
			type: "POST",
			url: base_url+"user/create_node", 
			data: {
				text:node.text
			},
			dataType: "text",  
			cache:false,
			success: 
				function(id){
					data.instance.set_id(node, id);
					data.instance.set_icon(id, 'fa fa-user');
					data.instance.edit(id);
				}
		});
    }).bind("rename_node.jstree", function (e, data) {
		$.ajax({
			type: "POST",
			url: base_url+"user/rename_node", 
			data: {id:data.node.id,text:data.text},
			dataType: "text",  
			cache:false,
			success: 
				function(data){
					
				}
		});
    }).bind("delete_node.jstree", function (e, data) {
		$.ajax({
			type: "POST",
			url: base_url+"user/delete_node", 
			data: {id:data.node.id},
			dataType: "text",  
			cache:false,
			success: 
				function(data){
					$("#content").html(data);
					$('#confirm_dialog').modal('hide');
				}
		});
    }).bind("select_node.jstree", function (e, data) {
		if(data.node.parents.length == 2)
		{
			$.ajax({
				type: "POST",
				url: base_url+"user/settings", 
				data: {id:data.node.id},
				dataType: "text",  
				cache:false,
				success: 
					function(data){
						$("#content").html(data);
					}
			});
		}
		else
			$("#content").html('');
    });
	$('#key_word').keyup(function (){$('#jstree').jstree(true).search($('#key_word').val());});
	$(document).on('click',"#confirm",function(e){
		$("#jstree").jstree(true).delete_node($('#user').val());
	});
});
</script>