var $j = jQuery.noConflict();

$j(document).ready(function(){
		
	/* tab indent */
	$j("textarea").indenthis();
	 	
});

function loadfile2(){

	ov_data = new Object();
	ov_data.ov_client = $j('#ov_client').val();
	ov_data.template = $j('#template').val();
	ov_data.ov_type = $j('#ov_type').val();
	ov_data.ov_element = $j('#ov_element').val();
	ov_data.view_name = $j('#view_name').val();
	ov_data.filename = $j('#filename').val();

/*	var DTO = { 'ov_data' : ov_data }; */
	
    $j.ajax({
      type: "POST",
      url: "index.php?option=com_jhacks&task=loadfile2",
	  data: {override: JSON.stringify(ov_data)},
      dataType: "json",
    /*  async: false, */
      success: function(returndata)
      {
		$j('#file_content').val(returndata.filecontent).keyup().focus();
	/*	$j('#filename_alternative').val($j('#filename').val().slice(0, -4)); */
		$j('#filename_override').val($j('#filename').val());
		$j('fieldset#item2_override p.itemselected').html(returndata.item2override + '\\' + $j('#filename').val());
		$j('fieldset#override_file p.itemselected').html(returndata.overridefile + '\\' + $j('#filename').val());
		window.location = "#override_file";
      },
      error: function()
      {
        alert("I cannot load the source code of the file");
      }
    });
}

function loadviews(what){

	ov_data = new Object();
	ov_data.ov_client = $j('#ov_client').val();
	ov_data.template = $j('#template').val();
	ov_data.ov_type = $j('#ov_type').val();
	ov_data.ov_element = $j('#ov_element').val();
	ov_data.view_name = $j('#view_name').val();
	ov_data.filename = $j('#filename').val();

    $j.ajax({
      type: "POST",
      url: "index.php?option=com_jhacks&task=loadviews",
      data: {loadwhat: what, override: JSON.stringify(ov_data)},
      dataType: "html",
    /*  async: false, */
      success: function(content)
      {
        $j("#"+ what).html(content);
      },
      error: function()
      {
        alert("I cannot load the specified dropdown list");
      }
    });	
	
}

function basename(path) {
	return path.replace(/\\/g,'/').replace( /.*\//, '' );
}
function dirname(path) {
	return path.replace(/\\/g,'/').replace(/\/[^\/]*$/, '');;
}