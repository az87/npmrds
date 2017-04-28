<div style="height: calc( 100vh - 110px );">
	<div class="container1" style="float: left;width:250px;margin-right:10px;">
		<input type="text" id="key_word" name="key_word" class="form-control" style="width:100%" value="" placeholder="Search ...">
		<div id="jstree" style="overflow:auto;height:calc( 100vh - 200px );">
		<ul>
			<li id="root" data-jstree='{ "opened" : "true"}'>Highways<ul><?php
				for($i=0;$i<count($highways);$i++)
					echo "<li id='".$highways[$i]['id']."'>".$highways[$i]['name'];					
				?></ul></li>
		</ul>
		</div>
	</div>
	<div class="container1" style="width:calc( 100% - 260px );overflow:auto;" id="content"></div>	
</div>

  <div class="modal fade" id="confirm_dialog" role="dialog">
    <div class="modal-dialog">
    <input type='hidden' id="highway" name='highway'>
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete Highway</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this highway ?</p>
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
		"plugins" : [ "contextmenu","search","themes","state","unique"],
		"state" : {
			"key" : "highways"
		},
		"core" : {
			"multiple" : false,
			"check_callback" : function(operation, node, node_parent, node_position, more) {
					return true;
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
								$('#highway').val($node.id);
								$('#confirm_dialog').modal('show');
							}
						}
					}
				}
				return items;
			}
		}
	}).bind("create_node.jstree", function (e, data) {
		var node = $.extend(true, {}, data.node);
		$.ajax({
			type: "POST",
			url: base_url+"highway/create_node", 
			data: {
				text:node.text
			},
			dataType: "text",  
			cache:false,
			success: 
				function(id){
					data.instance.set_id(node, id);
					data.instance.edit(id);
				}
		});
    }).bind("rename_node.jstree", function (e, data) {
		$.ajax({
			type: "POST",
			url: base_url+"highway/rename_node", 
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
			url: base_url+"highway/delete_node", 
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
				url: base_url+"highway/settings", 
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
		$("#jstree").jstree(true).delete_node($('#highway').val());
	});
});
</script>