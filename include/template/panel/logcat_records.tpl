<{include file ="header.tpl"}>
<{include file ="navibar.tpl"}>
<{include file ="sidebar.tpl"}>
<!-- TPLSTART 以上内容不需更改，保证该TPL页内的标签匹配即可 -->

<{$osadmin_action_alert}>
<{$osadmin_quick_note}>
<script>
// /**
// * 删除左右两端的空格
// */
// String.prototype.trim=function()
// {
//      return this.replace(/(^\s*)|(\s*$)/g, ”);
// }
// /**
// * 删除左边的空格
// */
// String.prototype.ltrim=function()
// {
//      return this.replace(/(^\s*)/g,”);
// }
// /**
// * 删除右边的空格
// */
// String.prototype.rtrim=function()
// {
//      return this.replace(/(\s*$)/g,”);
// }
function OFilter(){
	//this.key = key;//0空,1检索内容2,检索Tag
	//this.string = string;
}
OFilter.prototype.setKey=function(key){
	this.key = key
}
OFilter.prototype.setString = function(string) {
	this.string = string;
}
function JSAdminAlert(msg){
	var alert_html="<div class=\"alert alert-$type\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>" + msg +"</div>";
	$("#bar").append(alert_html);
}
function onRefresh (key,string) {
	//todo>need to config site root 
	var url = "http://127.0.0.1:81/OSAdmin/api/logcat.php"
	$.ajax({ url: url, success: function(data){
		//todo>error处理
        //$(this).addClass("done");
        //console.log(data);
        var arr = eval("["+data+"]");
        console.log(arr[0]["ret"]);
        if(arr[0]["ret"]!=0){
        	JSAdminAlert(arr[0]["msg"]);
        	return;
        }

        //alert(arr.length);
        //clear 
        arr = eval(arr[0]["msg"]);
        $("table tbody tr").empty();
        for(var key in arr){
 			//alert(arr[item]);
 			//速度其实会比较慢，around 1.4 sec
        	$("table tbody tr").first().before("<tr><td>" + key + "</td><td>2111</td><td>" + arr[key]+ "</td><td><a href='quicknote_modify.php?note_id=<{$note.note_id}>' title= '展开' ><i class='icon-pencil'></i></a>&nbsp;<a data-toggle='modal' href='#myModal'  title= '上下文' ><i class='icon-remove' href='quicknotes.php?method=del&note_id=<{$note.note_id}>#myModal' data-toggle='modal' ></i></a></td></tr>");	
        	//速度会快，too short 0.1 sec
        	// $("table tbody").append("<tr><td>" + key + "</td><td>2111</td><td>" + arr[key] + "</td><td></td></tr>");
        }
        
      }});
	//$("#tab     tbody").append("<tr><td>第二行文字</td></tr>");
}
function filter (argument) {
	//alert($("input:text[name='filter_string']").val());
	var ret = readFilter($("input:text[name='filter_string']").val());
	//alert(ret.key);
	var records = $("table tbody tr");
	for (var i=records.length-1;i>=0;i--) {
		console.log(records.string);
		if(records.eq(i).html().indexOf(ret.string) == -1 ){
     		//records.eq(i).remove();
     		records.eq(i).css('display', 'none');  
     		console.log("yeo");
		}else{
			console.log("contain");
			records.eq(i).css('display','');
		}
	}
}
function readFilter($value='')
{
	var ret = new OFilter();
	var words = $value.split(':');
	if($value=="") {
		ret.setKey(0);
		return ret;
	}
	if(words.length==2){
		ret.setKey(2);
		ret.setString(words[1]);
	}
	else{
		ret.setKey(1);
		ret.setString(words[0]);
	}
	return ret;
}
</script>
<div style="border:0px;padding-bottom:5px;height:auto">
	<div style="float:left;margin-right:5px">
		<label>可加检索条件,Tag:Unity等</label>
		<input type="text" name="filter_string" value="<{$_GET.user_name}>" placeholder="输入用户名" > 
	</div>

	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<a class="btn btn-primary" onclick="filter();"><i  class="icon-plus"></i>Filter</a>
		<a data-toggle="collapse" onclick="onRefresh();" title="检索"><button class="btn btn-primary" style="margin-left:5px"><i class="icon-search" ></i></button></a>
	</div>

	<div id="bar" style="clear:both;"></div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">Logcat Records</a>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
			<tr>
				<th>#</th>
				<th>所有者</th>
				<th>内容</th>
				<th width="80px">操作</th>
			</tr>
			</thead>
			<tbody>							  
			<{foreach name=note from=$quicknotes item=note}>
				 
				<tr>
				 
				<td><{$note.note_id}></td>
				<td><{$note.owner_name}></td>
				<td><{$note.note_content}></td>
				<td>
				<{if $user_group ==1 || $note.owner_id == $current_user_id }>
				<a href="quicknote_modify.php?note_id=<{$note.note_id}>" title= "修改" ><i class="icon-pencil"></i></a>
				&nbsp;
				<a data-toggle="modal" href="#myModal"  title= "删除" ><i class="icon-remove" href="quicknotes.php?method=del&note_id=<{$note.note_id}>#myModal" data-toggle="modal" ></i></a>
				<{/if}>
				</td>
				</tr>
			<{/foreach}>
		  </tbody>
		</table>
			<!--- START 分页模板 --->
				
               <{$page_html}>
					
			 <!--- END --->
	</div>
</div>

<!---操作的确认层，相当于javascript:confirm函数--->
<{$osadmin_action_confirm}>
	
<!-- TPLEND 以下内容不需更改，请保证该TPL页内的标签匹配即可 -->
<{include file="footer.tpl" }>