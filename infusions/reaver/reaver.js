var refreshId;

function stop_refresh(s)
{
    clearInterval(refreshId);
    refreshId=null;
    
    if(s==null)
        $("#start_ar").removeAttr('disabled');

    $("#stop_ar").attr('disabled','disabled');
    
    append_log("Auto-refresh disabled");
}

function start_refresh()
{
 
    refreshId = setInterval(function()
    {
        refresh_output();
    }, $('#auto_time').val());
    
    $("#start_ar").attr('disabled','disabled');
    $("#stop_ar").removeAttr('disabled');
    append_log("Auto-refresh enabled every "+($('#auto_time').val()/1000)+" sec");
}

function init() 
{
    $("#loading").ajaxStart(function(){
        $(this).show();
    }).ajaxStop(function(){
        $(this).hide();
    });
    refresh_radio();
    refresh_interfaces();
    refresh_monitors();
    clear_all();
}

function clear_all()
{
    clear_output();
    $("#victime").val("");
    $("#ap").val("");
    $("#log").val("");
    $('#button_start').attr('disabled',"disabled");
    $('#button_stop').attr("disabled",'disabled');
    disableAttackButtons();
}

function clear_output()
{
    $("#output").val("");
    append_log("Output cleared");
}

function disableAttackButtons()
{
    $('#button_refresh').attr('disabled',"disabled");
    $('#button_clear').attr('disabled',"disabled");
    $("#start_ar").attr('disabled','disabled');
    $('#stop_ar').attr("disabled",'disabled');
}


function refresh_radio() 
{
    $('#list_radio').load('reaver_actions.php?list&radio');
}
function refresh_interfaces() 
{
    $('#list_int').load('reaver_actions.php?list&int');
}

function refresh_monitors() 
{
    $('#list_mon').load('reaver_actions.php?list&mon');
}


function refresh_available_ap() 
{
    
    $.ajax({
        type: "GET",
        data: "available_ap&interface="+$("#interfaces").val(),
        url: "reaver_actions.php",
        success: function(msg){
            $("#list_ap").html(msg).slideDown();
            
            $('#survey-grid tr').click(function() 
            { 
                selectVictime($(this).attr("name"));
            });
            append_log("AP list refreshed");
           			   
				
        }
    });
}


function selectVictime(victime) 
{
    var arr  = victime.split(',');
    var ap=arr[0];
    var bssid=arr[1];
    var channel=arr[2];
    $("#ap").val(ap);
    $("#victime").val(bssid);
    $("#channel").val(channel);
    $('#button_start').removeAttr("disabled");
    append_log("Victime selected : "+bssid);
    
}

function refresh_output() 
{

    $.ajax({
        type: "GET",
        data: "reaver=1&refresh=1&bssid="+$("#victime").val(),
        url: "reaver_actions.php",
        success: function(msg){
            var psconsole = $('#output');
            if($("#output").val()!=msg)
            {
                $("#output").val(msg).scrollTop(psconsole[0].scrollHeight - psconsole.height());	
            }
        }
    });

}


function start_attack() 
{
    var inter = $('#mon').val();
    var v = $("#victime").val();
    var c = $("#channel").val();
    var option_S=$("#option_S").attr('checked');
    var option_a=$("#option_a").attr('checked');
    var option_c=$("#option_c").attr('checked');
    $.ajax({
        type: "GET",
        data: "reaver=1&start=1&interface="+inter+"&victime="+v+"&ch="+c+"&S="+option_S+"&a="+option_a+"&c="+option_c,
        url: "reaver_actions.php",
        success: function(msg){
            $('#button_start').attr('disabled',"disabled");
            $('#button_stop').removeAttr("disabled");
           
            $('#button_refresh').removeAttr("disabled");
            $('#button_clear').removeAttr('disabled');
            
            $('#start_ar').removeAttr("disabled");
            
            $("#list_ap").slideUp();
            $("#refresh_ap").attr("disabled",'disabled');
            $("#option_S").attr('disabled','disabled');
            $("#option_a").attr('disabled','disabled');
            $("#option_c").attr('disabled','disabled');
            append_log(msg);
            refresh_output();
            start_refresh();
        }
    });
	
	
}

function stop_attack() 
{
    

    $.ajax({
        type: "GET",
        data: "reaver=1&stop=1",
        url: "reaver_actions.php",
        success: function(msg){
            append_log(msg);
            $('#button_stop').attr('disabled',"disabled");
            $('#button_start').removeAttr("disabled");
            disableAttackButtons();
            $("#option_S").removeAttr('disabled');
            $("#option_a").removeAttr('disabled');
            $("#option_c").removeAttr('disabled');
            $("#list_ap").slideDown();
            $("#refresh_ap").removeAttr("disabled");
            stop_refresh('stop');
            
        }
    });
	
}

function start_mon() 
{
    var inter = $('#interfaces').val();
    if(inter=='')
        alert("No interface selected...");
    else
    {
        $.ajax({
            type: "GET",
            data: "mon_start&interface="+inter,
            url: "reaver_actions.php",
            success: function(msg){
                append_log(msg);
                refresh_monitors();			
            }
        });
    }
}

function stop_mon() 
{
    var inter = $('#mon').val();
    if(inter=='')
        alert("No monitor interface selected...");
    else
    {
        $.ajax({
            type: "GET",
            data: "mon_stop&interface="+inter,
            url: "reaver_actions.php",
            success: function(msg){
                append_log(msg);
                refresh_monitors();			
            }
        });
    }
}


function append_log(line)
{
    var now = new Date().toUTCString();
    line = now+"\n"+line+"\n------------------\n";
    $("#log").val( $("#log").val()+line).scrollTop($("#log")[0].scrollHeight - $("#log").height());
}


function install_reaver()
{
    var d="reaver&install";
    
    if($('#onusb').attr('checked')=="true")
        d+="&onusb";
    
    $.ajax({
        type: "GET",
        data: d,
        url: "reaver_actions.php",
        success: function(msg){
            append_log(msg);
            location.reload();			
        }
    });
}


function up_int(inter) 
{
//    var inter = $('#interfaces').val();
    if(inter=='')
        alert("No interface selected...");
    else
    {
        $.ajax({
            type: "GET",
            data: "up&interface="+inter,
            url: "reaver_actions.php",
            success: function(msg){
                append_log(msg);
                refresh_radio();
                refresh_interfaces();
            }
        });
    }
}

function down_int(inter) 
{
//    var inter = $('#interfaces').val();
    if(inter=='')
       alert("No interface selected...");
    else
    {
        $.ajax({
            type: "GET",
            data: "down&interface="+inter,
            url: "reaver_actions.php",
            success: function(msg){
                append_log(msg);
                refresh_radio();
                refresh_interfaces();		
            }
        });
    }
}

