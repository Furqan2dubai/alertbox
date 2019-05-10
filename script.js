if(ajax_object.msg){
    alert(ajax_object.msg);
}
else if(ajax_object.baseurl){

    var baseurl = ajax_object.baseurl;
    
    function get_list(e){
        type = e.id; 
        if(jQuery(e).is(':checked')){
            jQuery.ajax({
                url: baseurl+'/wp-json/wp/v2/'+type, 
                dataType: 'json',
                success: (e)=> { 
                    jQuery.each(e, (i,a)=>{
                        jQuery("#list").append('<li class="'+type+'"><input type="checkbox" onclick="a(this)" class="post" value="'+ a.id + '" name="postId"/>'+ a.title.rendered + '</li>');
                    });
                    $ids = jQuery("#postId").val().split(',');
                    jQuery.each($ids, (i,a)=>{
                        jQuery(".post[value="+a+"]").prop("checked",true);
                    });
                }
            });
        }
        else{
            jQuery("#list li."+type).remove();
        }  
    } 
    
    function a(e){ 
    
        var data = {
            'action': 'my_action',
            'postId': e.value       
        }; 
        ids = Array.from(jQuery(".post:checked").map((i,v)=> v.value)).toString();
        jQuery("#postId").val(ids);
        // jQuery.post(ajax_object.ajax_url, data, function(response) {
        // 	alert('Got this from the server: ' + response);
        // });
    }
}