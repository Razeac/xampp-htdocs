$( document ).ready(function() {
    var ajax_link = "lib/class/pod.class.php";
    var remarks="";
    var slip="";
    
    var connected="";

    var title_ip = $(".client_ip").text();
    $(document).find("title").text("PODams--Client::"+title_ip);
    $('.student_id').focus();
    $('.faculty_id').focus();
      
    function showTime() {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var session = "AM"; 
  
        if ( hours == 0 ) {
            hours = 12;
        } 
  
        if( hours >= 12 ){
            session = "PM";
        }
  
        if ( hours > 12 ){
            hours = hours - 12;
        }
  
        hours = ( hours < 10 ) ? hours = "0" + hours : hours;
        minutes = ( minutes < 10 ) ? minutes = "0" + minutes : minutes;
        seconds = ( seconds < 10 ) ? seconds = "0" + seconds : seconds;
  
        var realtime = hours + ":" + minutes + ":" + seconds + " " + session;
        //console.log(realtime);
 
        $('.hours').text(hours); 
        $('.minutes').text(minutes); 
        $('.seconds').text(seconds); 
        $('.median').text(session); 
  
        setTimeout( showTime, 1000 );
    }
    showTime();

    function focus() {
        $('.student_id').focus();
        setTimeout( focus, 1 );
    }
    focus();

    function pingserver() {
        if(connected=="true") {
            $.Ping("http://192.168.2.235" /*, optional timeout */).done(function (success, url, time, on) {
                data="Server Connection Success";
                $(".connection_status").removeClass("alert-danger").addClass( "alert-success" );
                $(".connection_status").html(data);
            }).fail(function (failure, url, time, on) {
                data="Server Connection Failed";
                $(".connection_status").removeClass("alert-success").addClass( "alert-danger" );
                $(".connection_status").html(data);
            });
        }
            $.Ping("http://192.168.2.235" /*, optional timeout */).done(function (success, url, time, on) {
                return true;
                console.log("success");
            }).fail(function (failure, url, time, on) {
                data="Server Connection Failed";
                $(".connection_status").removeClass("alert-success").addClass( "alert-danger" );
                $(".connection_status").html(data);
                $(".student_id").prop( "disabled", true );
                console.log("failed");
                connected = "false";
            });
        
        setTimeout( pingserver, 1000 );
    }
    pingserver();
    
    function checkclient() {
        var ip = $(".client_ip").text();
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'ip='+ ip + '&request_type=' + 'checkclient',
            dataType: 'json',
            success: function(info) {
                if(info.ab == 0) {
                    data="Client Not Connected to the server";
                    $(".student_id").prop( "disabled", true );
                    $(".connection_status").removeClass("alert-success").addClass( "alert-danger" );
                    $(".connection_status").html(data);
                    console.log("client-server connect false");
                    connected = "false";
                } else {
                    console.log("client-server connect true");
                    connected = "true";
                }
            }
        });
        setTimeout( checkclient, 1000 );
    }
    checkclient();

    function checkrefresh() {
        var ip = $(".client_ip").text();
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'ip='+ ip + '&request_type=' + 'checkrefresh',
            dataType: 'json',
            success: function(info) {
                if(info.ab == 1) {
                    stoprefresh();
                    location.reload();
                }
            }
        });
        setTimeout( checkrefresh, 10000 );
    }
    checkrefresh();

    function stoprefresh() {
        var ip = $(".client_ip").text();
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'ip='+ ip + '&request_type=' + 'stoprefresh',
            dataType: 'json',
            success: function(info) {
                return true;
            }
        });
    }

    $(".client_connect").click(function() {
        client_ip();
    });

    function client_ip() {
        var name = $(".client_name").text();
        var ip = $(".client_ip").text();
        console.log(ip);
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'name='+ name + '&ip='+ ip + '&request_type=' + 'client_ip',
            dataType: 'json',
            success: function(info) {
            }
        });
    }

    function auto_script() {
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'request_type=' + 'get_websettingsinfo',
            dataType: 'json',
            success: function(info) {
                remarks = info.ab;
                slip = info.bc;

                if(info.bc=="absent") {
                    $(".student_id").prop( "disabled", true );
                    $(".student_id").val('');
                } else {
                    console.log(connected);
                    if(connected == "true") {
                        $(".student_id").prop( "disabled", false );
                    } else {
                        $(".student_id").prop( "disabled", true );
                        $(".student_id").val('');
                    }
                }
            }
        });
        setTimeout( auto_script, 1000 );
    }
    auto_script();

    $( '.student_id' ).keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            console.log($(this).val());
            time_in();
            var $this = $(this);
            $this.select();

            $this.mouseup(function() {
                $this.unbind("mouseup");
                return false;
            });
        }
    });

    $( '.faculty_id' ).keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            console.log($(this).val());
            time_fac_in();
            var $this = $(this);
            $this.select();

            $this.mouseup(function() {
                $this.unbind("mouseup");
                return false;
            });
        }
    });

    function time_fac_in() {
        var faculty_id = $('.faculty_id').val();
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 

        var time = hours + ":" + minutes + ":" + seconds + " " + median;

        var image_cnt = $(".faculty_picture");

        $.ajax({ 
            type: "POST", 
            url: ajax_link, 
            data: 'faculty_id='+ faculty_id + '&median='+ median + '&request_type=' + 'faculty_id',
            dataType: 'json',
            success: function(info) { 
                console.log(info);
                if(info.ef == "0") {
                    image_cnt.attr("src", info.cd);
                    $(".name").text(info.de);
                    $(".time_in").text(info.bc);
                    $(".time_out").text(info.ab);
                    $(".time_picture").text(info.ab);
                } else {
                    image_cnt.attr("src", info.cd);
                    $(".name").text(info.bc);
                    $(".time_out").text(info.ab);
                    $(".time_picture").text(info.ab);
                    $(".time_in").text(time);
                }
            }
        });
        return false;
    }
    
    function time_in() {
        var student_id = $('.student_id').val();
        
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 

        var time = hours + ":" + minutes + ":" + seconds + " " + median;

        var image_cnt = $(".student_picture");

        $.ajax({
            type: "POST", 
            url: ajax_link, 
            data: 'student_id='+ student_id + '&remarks='+ remarks + '&slip='+ slip  + '&median='+ median + '&request_type=' + 'student_id',
            dataType: 'json',
            success: function(info) { 
                console.log(info);
                if(info.ef == "0") {
                    image_cnt.attr("src", info.cd);
                    $(".name").text(info.bc);
                    $(".grade_section").text(info.bc);
                    $(".remarks").text(info.ab);
                    $(".time_in").text(info.ab);
                } else {
                    image_cnt.attr("src", info.cd);
                    $(".name").text(info.ab);
                    $(".grade_section").text(info.bc);
                    $(".remarks").text(info.de);
                    $(".time_in").text(time);
                }
            }
        });
        return false;
    }
    
    function get24Hr(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var seconds = Number(time.match(/:(\d+):(\d+)/)[2]);
        var AMPM = time.match(/\s(.*)$/)[1];
        if(AMPM == "PM" && hours<12) hours = hours+12;
        if(AMPM == "AM" && hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if(hours<10) sHours = "0" + sHours;
        if(minutes<10) sMinutes = "0" + sMinutes;
        return latest_time = sHours + ":" + sMinutes + ":";
    }

});
  
  
  
  