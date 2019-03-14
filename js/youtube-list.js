jQuery(document).ready(function($){

    var ytkey = mypc_options.apikey;
    var ytnumvids = mypc_options.numvids;
    var ytchannelid = mypc_options.yih4searchby;
    var referrer = mypc_options.referrer;
    var ytsiteurl = mypc_options.yih4admurl;
    var ytchannelID = 'channelId: '+ mypc_options.yih4searchby;
    //youtubeApiCall();
    $("#pageTokenNext").on( "click", function( event ) {
        $("#pageToken").val($("#pageTokenNext").val());
        if (mypc_options.yih4searchby){
          youtubeApiCallchannel();
        } else {youtubeApiCall();}
    });
    $("#pageTokenPrev").on( "click", function( event ) {
        $("#pageToken").val($("#pageTokenPrev").val());
        if (mypc_options.yih4searchby){
          youtubeApiCallchannel();
        } else {youtubeApiCall();}
    });
    $("#hyv-searchBtn").on( "click", function( event ) {
      if (mypc_options.yih4searchby){
        youtubeApiCallchannel();
      } else {youtubeApiCall();}
        return false;
    });
    $( "#hyv-search" ).autocomplete({
      source: function( request, response ) {
        //console.log(request.term);
        var sqValue = [];
        $.ajax({
            type: "POST",
            url: "https://suggestqueries.google.com/complete/search?hl=en&ds=yt&client=youtube&hjson=t&cp=1",
            dataType: 'jsonp',
            headers: {'X-Alt-Referer': referrer },
            data: $.extend({
                q: request.term
            },{  }),
            beforeSend: function() {
              $('#yih4RSenterkey').html("<img src='" + ytsiteurl + "/wp-includes/images/spinner-2x.gif' width='20' height='20'/>");
            },
            success: function(data){
                console.log(data[1]);
                obj = data[1];
                $.each( obj, function( key, value ) {
                    sqValue.push(value[0]);
                });
                response( sqValue);
            }
        });
      },
      select: function( event, ui ) {
        setTimeout( function () {
            if (mypc_options.yih4searchby){
              youtubeApiCallchannel();
            } else {youtubeApiCall();}
        }, 1000);
      }
    });

    function youtubeApiCall(){
        $.ajax({
            cache: false,
            data: $.extend({
                type: 'video',
                order: 'date',
                key: ytkey,
                q: $('#hyv-search').val(),
                part: 'snippet'
            }, {maxResults:ytnumvids,pageToken:$("#pageToken").val()}),
            dataType: 'json',
            headers: {'X-Alt-Referer': referrer },
            type: 'GET',
            timeout: 5000,
            fields: "pageInfo,items(id(videoId)),nextPageToken,prevPageToken",
            url: 'https://www.googleapis.com/youtube/v3/search',
            beforeSend: function(){
              $('#yih4RSenterkey').html("<img src='" + ytsiteurl + "/wp-includes/images/spinner-2x.gif' width='20' height='20'/>");
            },
            statusCode: {
                400: function (xhr) {
                    console.log('400 response');
                },
                403: function (xhr) {
                    console.log('403 response');
                },
                404: function (xhr) {
                    console.log('404 response');
                }
            },
            error: function (xhr, desc, err) {
              if (xhr.status==400){
                // $('#yih4RSenterkey').html("<span>"+xhr.status+" "+desc+" "+err+"</span>");
                $('#hyv-watch-content').hide();
                $('#hyv-watch-channel-brand-div').hide();
                $('#yih4RSenterkey').html("HTTP Error 400 Bad Request - Invalid URL");
              }
              if (xhr.status==403){
                $('#hyv-watch-content').hide();
                $('#hyv-watch-channel-brand-div').hide();
                $('#yih4RSenterkey').html("HTTP Error 403 Forbidden");
              }
              if (xhr.status==404){
                $('#hyv-watch-content').hide();
                $('#hyv-watch-channel-brand-div').hide();
                $('#yih4RSenterkey').html("HTTP Error 404 Not Found");
              }
            }
        })
        .done(function(data) {
            $('.btn-group').show();
            if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
            var items = data.items, videoids = [];
            $("#pageTokenNext").val(data.nextPageToken);
            $("#pageTokenPrev").val(data.prevPageToken);
            $.each(items, function(index,e) {
                videoids.push(e.id.videoId);
            });
            getVideoDetails(videoids.join());
        });
    }

    function getVideoDetails(ids){
        $.ajax({
            cache: false,
            data: $.extend({
                order: 'date',
                key: ytkey,
                part: 'snippet,contentDetails,statistics'
            }, {id: ids}),
            dataType: 'json',
            headers: {'X-Alt-Referer': referrer },
            type: 'GET',
            timeout: 5000,
            fields: "items(id,contentDetails,statistics,snippet(publishedAt,channelTitle,channelId,title,description,thumbnails(medium)))",
            url: 'https://www.googleapis.com/youtube/v3/videos',
        })


        .done(function(data) {
            var items = data.items, videoList = "";
            $.each(items, function(index,e) {

              // Get the published Date
              var ytdate = new Date(e.snippet.publishedAt);
                var month = new Array();
                  month[0] = "January";
                  month[1] = "February";
                  month[2] = "March";
                  month[3] = "April";
                  month[4] = "May";
                  month[5] = "June";
                  month[6] = "July";
                  month[7] = "August";
                  month[8] = "September";
                  month[9] = "October";
                  month[10] = "November";
                  month[11] = "December";
                  var formattedDate = month[ytdate.getMonth()] + " " + (ytdate.getDate() + 1) + ", " + ytdate.getFullYear();

                // Format Stats Numbers with commas
                var nf = Intl.NumberFormat();
                // Get Views
                var views = nf.format(e.statistics.viewCount);
                // Get Likes
                var likes = nf.format(e.statistics.likeCount);
                var dislikes = nf.format(e.statistics.dislikeCount);

                // Get Category ID and Assign a Value
                var ytCats = e.snippet.categoryId;
                  var cats = new Array();
                    cats[1] = 'Film & Animation';
                    cats[2] = 'Autos & Vehicles';
                    cats[10] = 'Music';
                    cats[15] = 'Pets & Animals';
                    cats[17] = 'Sports';
                    cats[18] = 'Short Movies';
                    cats[19] = 'Travel & Events';
                    cats[20] = 'Gaming';
                    cats[21] = 'Videoblogging';
                    cats[22] = 'People & Blogs';
                    cats[23] = 'Comedy',
                    cats[24] = 'Entertainment';
                    cats[25] = 'News & Politics';
                    cats[26] = 'Howto & Style';
                    cats[27] = 'Education';
                    cats[28] = 'Science & Technology';
                    cats[29] = 'Nonprofits & Activism';
                    cats[30] = 'Movies';
                    cats[31] = 'Anime/Animation';
                    cats[32] = 'Action/Adventure';
                    cats[33] = 'Classics';
                    cats[36] = 'Drama';
                    cats[37] = 'Family';
                    cats[38] = 'Foreign';
                    cats[39] = 'Horror';
                    cats[40] = 'Sci-Fi/Fantasy';
                    cats[41] = 'Thriller';
                    cats[42] = 'Shorts';
                    cats[43] = 'Shows';
                    cats[44] = 'Trailers';
                    var ytcategory = cats[ytCats];
                    var sp = '&nbsp;&nbsp;';

                function yih4ValidateInput( html ) {
                    return $( $.parseHTML(html) ).text();
                }

                var yih4_chkbx_val = yih4ValidateInput(e.id);

                // Setup the html list data
                listItem = '<li class="hyv-video-list-item"><div class="hyv-content-wrapper"><div class="ytinput">';
                listItem2 = '<input type="checkbox" class="chk_boxes1" name="video_list[]" id="youtubevideo" value="'+yih4_chkbx_val+'"></input></div>';
                listItem3 = '<a href="" class="hyv-content-link show-modal" alt="'+e.snippet.title+'" title="'+e.snippet.title+'" value="'+e.id+'">';
                listItem4 = '<span class="title">'+e.snippet.title+'</span><span class="stat">Published on <span>'+formattedDate+'</span> by <span>'+e.snippet.channelTitle+'</span><span> in '+ytcategory+'</span></span>';
                stats = '<br/><span class="stat"><span><i class="fa fa-thumbs-up" aria-hidden="true"></i> '+likes+sp+'</span><span>'+sp+'<i class="fa fa-thumbs-down" aria-hidden="true"></i> '+dislikes+'</span><span style="padding-left:10px;border-bottom:2px solid #bb0000;display:inline-block;float:right;"><strong><i class="fa fa-eye" aria-hidden="true"></i> '+views+' views</strong></span></span>';
                endstats = '</a></div>';
                listItem5 = '<div class="hyv-thumb-wrapper"><a href="" class="show-modal hyv-thumb-link" alt="Click the thumbnail to preview video in popup window" value="'+e.id+'">';
                listItem6 = '<span class="hyv-simple-thumb-wrap"><img alt="'+e.snippet.title+'" src="'+e.snippet.thumbnails.default.url+'" width="120" height="90"></span></a>';
                listItem7 = '<span class="video-time">'+YTDurationToSeconds(e.contentDetails.duration)+ '</span></div></li>';
                videoList = videoList + listItem + listItem2 + listItem3 + listItem4 + stats + endstats + listItem5 + listItem6 + listItem7;
            });
            $("#hyv-watch-related").html(videoList);
            $('#yih4RSenterkey').html("");
            $( ".show-modal" ).each(function(index) {
              $(this).on('click', function() {
                event.preventDefault();
                  $.showYtVideo({
                      videoId: $(this).attr('value')
                  });
              });
            });
        });
    }

    function youtubeApiCallchannel(){
        $.ajax({
            cache: false,
            data: $.extend({
                type: 'video',
                order: 'date',
                channelId: mypc_options.yih4searchby,
                part: 'snippet',
                key: ytkey
            }, {maxResults:ytnumvids,pageToken:$("#pageToken").val()}),
            dataType: 'json',
            headers: {'X-Alt-Referer': referrer },
            type: 'GET',
            timeout: 5000,
            fields: "pageInfo,items(id(videoId)),nextPageToken,prevPageToken",
            url: 'https://www.googleapis.com/youtube/v3/search',
            beforeSend: function(){
              $('#yih4RSenterkey').html("<img src='" + ytsiteurl + "/wp-includes/images/spinner-2x.gif' width='20' height='20'/>");
            },
        })

        .done(function(data) {
            $('.btn-group').show();
            if (typeof data.prevPageToken === "undefined") {$("#pageTokenPrev").hide();}else{$("#pageTokenPrev").show();}
            if (typeof data.nextPageToken === "undefined") {$("#pageTokenNext").hide();}else{$("#pageTokenNext").show();}
            var items = data.items, videoids = [];
            $("#pageTokenNext").val(data.nextPageToken);
            $("#pageTokenPrev").val(data.prevPageToken);
            $.each(items, function(index,e) {
                videoids.push(e.id.videoId);
            });
            getVideoDetailschannel(videoids.join());
        });
    }

    function getVideoDetailschannel(ids){
        $.ajax({
            cache: false,
            data: $.extend({
                order: 'date',
                channelId: mypc_options.yih4searchby,
                part: 'snippet,contentDetails,statistics',
                key: ytkey
            }, {id: ids}),
            dataType: 'json',
            headers: {'X-Alt-Referer': referrer },
            type: 'GET',
            timeout: 5000,
            fields: "items(id,contentDetails,statistics,snippet(publishedAt,channelTitle,channelId,title,description,thumbnails(medium)))",
            url: 'https://www.googleapis.com/youtube/v3/videos',
        })


        .done(function(data) {
            var items = data.items, videoList = "";

            $.each(items, function(index,e) {

            // Get the published Date
            var ytdate = new Date(e.snippet.publishedAt);
              var month = new Array();
                month[0] = "January";
                month[1] = "February";
                month[2] = "March";
                month[3] = "April";
                month[4] = "May";
                month[5] = "June";
                month[6] = "July";
                month[7] = "August";
                month[8] = "September";
                month[9] = "October";
                month[10] = "November";
                month[11] = "December";
                var formattedDate = month[ytdate.getMonth()] + " " + (ytdate.getDate() + 1) + ", " + ytdate.getFullYear();

              // Format Stats Numbers with commas
              var nf = Intl.NumberFormat();
              // Get Views
              var views = nf.format(e.statistics.viewCount);
              // Get Likes
              var likes = nf.format(e.statistics.likeCount);
              var dislikes = nf.format(e.statistics.dislikeCount);

              // Get Category ID and Assign a Value
              var ytCats = e.snippet.categoryId;
                var cats = new Array();
                  cats[1] = 'Film & Animation';
                  cats[2] = 'Autos & Vehicles';
                  cats[10] = 'Music';
                  cats[15] = 'Pets & Animals';
                  cats[17] = 'Sports';
                  cats[18] = 'Short Movies';
                  cats[19] = 'Travel & Events';
                  cats[20] = 'Gaming';
                  cats[21] = 'Videoblogging';
                  cats[22] = 'People & Blogs';
                  cats[23] = 'Comedy',
                  cats[24] = 'Entertainment';
                  cats[25] = 'News & Politics';
                  cats[26] = 'Howto & Style';
                  cats[27] = 'Education';
                  cats[28] = 'Science & Technology';
                  cats[29] = 'Nonprofits & Activism';
                  cats[30] = 'Movies';
                  cats[31] = 'Anime/Animation';
                  cats[32] = 'Action/Adventure';
                  cats[33] = 'Classics';
                  cats[36] = 'Drama';
                  cats[37] = 'Family';
                  cats[38] = 'Foreign';
                  cats[39] = 'Horror';
                  cats[40] = 'Sci-Fi/Fantasy';
                  cats[41] = 'Thriller';
                  cats[42] = 'Shorts';
                  cats[43] = 'Shows';
                  cats[44] = 'Trailers';
                  var ytcategory = cats[ytCats];
                  var sp = '&nbsp;&nbsp;';

                // Setup the html list data
                listItem = '<li class="hyv-video-list-item"><div class="hyv-content-wrapper"><div class="ytinput">';
                listItem2 = '<input type="checkbox" class="chk_boxes1" name="video_list[]" id="youtubevideo" value="'+e.id+'"></input></div>';
                listItem3 = '<a href="" class="hyv-content-link show-modal" alt="'+e.snippet.title+'" title="'+e.snippet.title+'" value="'+e.id+'">';
                listItem4 = '<span class="title">'+e.snippet.title+'</span><span class="stat">Published on <span>'+formattedDate+'</span> by <span>'+e.snippet.channelTitle+'</span><span> in '+ytcategory+'</span></span>';
                stats = '<br/><span class="stat"><span><i class="fa fa-thumbs-up" aria-hidden="true"></i> '+likes+sp+'</span><span>'+sp+'<i class="fa fa-thumbs-down" aria-hidden="true"></i> '+dislikes+'</span><span style="padding-left:10px;border-bottom:2px solid #bb0000;display:inline-block;float:right;"><strong><i class="fa fa-eye" aria-hidden="true"></i> '+views+' views</strong></span></span>';
                endstats = '</a></div>';
                listItem5 = '<div class="hyv-thumb-wrapper"><a href="" class="show-modal hyv-thumb-link" alt="Click the thumbnail to preview video in popup window" value="'+e.id+'">';
                listItem6 = '<span class="hyv-simple-thumb-wrap"><img alt="'+e.snippet.title+'" src="'+e.snippet.thumbnails.default.url+'" width="120" height="90"></span></a>';
                listItem7 = '<span class="video-time">'+YTDurationToSeconds(e.contentDetails.duration)+ '</span></div></li>';
                videoList = videoList + listItem + listItem2 + listItem3 + listItem4 + stats + endstats + listItem5 + listItem6 + listItem7;
            });
            $("#hyv-watch-related").html(videoList);
            // JSON Responce to display for user
            new PrettyJSON.view.Node({
                el:$(".hyv-watch-sidebar-body"),
                data:data
            });
            $('#yih4RSenterkey').html("");
            $( ".show-modal" ).each(function(index) {
              $(this).on('click', function() {
                event.preventDefault();
                  $.showYtVideo({
                      videoId: $(this).attr('value')
                  });
              });
            });
        });
    }

    function YTDurationToSeconds(duration) {
        var match = duration.match(/PT(\d+H)?(\d+M)?(\d+S)?/);
        var hours = ((parseInt(match[1]) || 0) !== 0)?parseInt(match[1])+":":"";
        var minutes = ((parseInt(match[2]) || 0) !== 0)?parseInt(match[2])+":":"";
        var seconds = ((parseInt(match[3]) || 0) !== 0)?parseInt(match[3]):"00";
        var total = hours + minutes + seconds;
        return total;
    }

});
