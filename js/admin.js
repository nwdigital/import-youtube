// Script for "Check All" checkboxes
jQuery(document).ready(function($){

  /**
   * @name jQuery show YouTube Video List plugin
   * @author Mathew Moore
   * @description Show video list
   * @version 1.0
   */

      var ytkey = mypc_options.apikey;
      var referrer = mypc_options.referrer;
      var ytsiteurl = mypc_options.referrer;
      var yih4admurl = mypc_options.yih4admurl;

  // API Key validator
  $('#submit-button-api-key').on('click', function(e){
    apikey = $('#yih4_nwd_yt_api_key').val();

    e.preventDefault();
    $.ajax({
        url: 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=YouTube+Data+API&type=video&key='+ apikey,
        type: 'GET',
        headers: {'X-Alt-Referer': referrer },
        dataType: 'jsonp',
        beforeSend: function(){
          $("#api_key_validate_resonse").html('&nbsp;&nbsp;Verifying Your Key');
        },
        success: function(response){
          var apikey = $('#yih4_nwd_yt_api_key').val();
          var valid = response.kind;
          if (valid) {
            update_api_key(valid, apikey);
          }
          else {
            var invalid = response.error.errors[0].reason;
            update_api_key(invalid, '');
          }
        },
    })
  })

  function update_api_key(validate, apikey){
    // Update the settings option value for the new api key
    apikey = apikey;
      $.ajax({
          url: mypc_options.ajaxurl,
          type: 'POST',
          data: {
            action: 'yih4_nwd_validate_api_key',
            username: $('#yih4_search_username').val(),
            apikey: apikey,
          },
          success: function(response){
            if(validate == 'youtube#searchListResponse') {
              var importurl = '/edit.php?post_type=yih4-video&page=youtube_import_nwdigital&tab=display_import_videos';
              $("#api_key_validate_resonse").css('color', '#01ad0d');
              $("#api_key_validate_resonse").html('&nbsp;&nbsp;Validation Successful');
              $("#api_key_validate_resonse").append('<a href="'+yih4admurl+importurl+'"> - Import Videos</a>');
            }
            if(validate == 'keyInvalid') {
              $("#api_key_validate_resonse").css('color', 'red');
              $("#api_key_validate_resonse").html('&nbsp;&nbsp;Validation Failed: invalid key');
            }
          }
      })
  }

  // Checkbox function on the import page
  $('.chk_boxes').click(function(){
      var chk = $(this).attr('checked')?true:false;
      $('.chk_boxes1').attr('checked',chk);
  });
  // Import type select box hide and show based on selection
  $('#yih4_search_type').on('change', function(){
    if($(this).val() == 'everything'){
      $('#yih4_everything_search').show();
      $('#yih4_username_search').hide();
      $('#yih4_channelid_search').hide();
    } else {$('#yih4_everything_search').hide();}
    if($(this).val() == 'channel'){
      $('#yih4_channelid_search').show();
    } else {$('#yih4_channelid_search').hide();}
    if($(this).val() == 'username'){
      $('#yih4_username_search').show();
    } else {$('#yih4_username_search').hide();}
  })
  // Username Search
  $('#submit-button-search-username').click(function(e){
    e.preventDefault();
    $.ajax({
        url: mypc_options.ajaxurl,
        type: 'POST',
        data: {
          action: 'yih4_nwd_username_to_channelid',
          username: $('#yih4_search_username').val(),
        },
        success: function(response){
          $("#uname_to_channelid_ajax").val(response);
          youtubeApiCallchannel();
          // $("#hyv-searchBtn").trigger('click');
        }
    })
  })
  // ChannelID Search
  $('#submit-button-search-channelid').click(function(e){
    e.preventDefault();
    $("#uname_to_channelid_ajax").val($('#yih4_search_channelid').val());
    youtubeApiCallchannel();
  })

    // Load some search results on page load
    if(ytkey) {
      youtubeApiCall();
    }
    // Show YouTube List on Button Click
    $('#searchBtn').click(function(e){
      e.preventDefault();
      youtubeApiCall();
    });
    $('#hyv-searchBtn').click(function(e){
      e.preventDefault();
    });

    //youtubeApiCall();
    $("#pageTokenNext").on( "click", function( event ) {
        $("#pageToken").val($("#pageTokenNext").val());
        if ($('#yih4_search_type').val() != 'everything'){
          youtubeApiCallchannel();
        } else {youtubeApiCall();}
    });
    $("#pageTokenPrev").on( "click", function( event ) {
        $("#pageToken").val($("#pageTokenPrev").val());
        if ($('#yih4_search_type').val() != 'everything'){
          youtubeApiCallchannel();
        } else {youtubeApiCall();}
    });
    $("#hyv-searchBtn").on( "click", function( event ) {
      if ($('#yih4_search_type').val() != 'everything'){
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
            if ($('#yih4_search_type').val() != 'everything'){
              youtubeApiCallchannel();
            } else {youtubeApiCall();}
        }, 1000);
      }
    });

    function youtubeApiCall(){
        filter_number_vids = parseInt($('#yih4_nwd_yt_channel_num_vids').val());
        $.ajax({
            cache: false,
            data: $.extend({
                type: 'video',
                order: 'date',
                key: ytkey,
                q: $('#hyv-search').val(),
                part: 'snippet'
            }, {maxResults:filter_number_vids,pageToken:$("#pageToken").val()}),
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
                $('#yih4RSenterkey').html("HTTP Error 400 Bad Request - Invalid URL");
              }
              if (xhr.status==403){
                $('#yih4RSenterkey').html("HTTP Error 403 Forbidden");
              }
              if (xhr.status==404){
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
      filter_number_vids = parseInt($('#yih4_nwd_yt_channel_num_vids').val());
        $.ajax({
            cache: false,
            data: $.extend({
                type: 'video',
                order: 'date',
                channelId: $('#uname_to_channelid_ajax').val(),
                part: 'snippet',
                key: ytkey
            }, {maxResults:filter_number_vids,pageToken:$("#pageToken").val()}),
            dataType: 'json',
            headers: {'X-Alt-Referer': referrer },
            type: 'GET',
            timeout: 5000,
            fields: "pageInfo,items(id(videoId)),nextPageToken,prevPageToken",
            url: 'https://www.googleapis.com/youtube/v3/search',
            beforeSend: function(){
              $('#yih4RSenterkey').html("<img src='" + ytsiteurl + "/wp-includes/images/spinner-2x.gif' width='20' height='20'/>");
            },
            error: function (xhr, desc, err) {
              if (xhr.status==400){
                // $('#yih4RSenterkey').html("<span>"+xhr.status+" "+desc+" "+err+"</span>");
                $("#yih4RSenterkey").css('color', 'red');
                $('#yih4RSenterkey').html("Oops, something went wrong. I couldn't find that " + $('#yih4_search_type').val() + ".");
              }
              if (xhr.status==403){
                $('#yih4RSenterkey').html("HTTP Error 403 Forbidden");
              }
              if (xhr.status==404){
                $('#yih4RSenterkey').html("HTTP Error 404 Not Found");
              }
            }
        })

        .done(function(data) {
            var yih4TotalResults = data.pageInfo.totalResults;
            var yih4ChannelTitle = data.items[0].snippet.channelTitle;

            if($('#yih4_search_type').val() == 'channel'){
              $("#yih4RSenterkey").css('color', 'black');
              $("#yih4RSenterkey").html('Showing '+ yih4TotalResults +' results for ' + yih4ChannelTitle);
            } else {}
            if($('#yih4_search_type').val() == 'username'){
              $("#yih4RSenterkey").css('color', 'black');
              $("#yih4RSenterkey").html('Showing '+ yih4TotalResults +' results for ' + yih4ChannelTitle);
            } else {}

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
        var yih4ChannelTitle;

        $.ajax({
            cache: false,
            data: $.extend({
                order: 'date',
                channelId: $('#uname_to_channelid_ajax').val(),
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
            // JSON Response to display for user
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

/**
 * @name jQuery show YouTube Popup window plugin
 * @author Mathew Moore
 * @description Show modal popup window
 * @version 1.0
 */

jQuery(document).ready(function($) {
    $.showYtVideo = function(options) {

        options = $.extend({
            modalSize: 'm',
            shadowOpacity: 0.5,
            shadowColor: '#000',
            clickOutside: 1,
            closeButton: 1,
            videoId: ''
        }, options);

        var modal = $('<div class="yih4-modal size-' + options.modalSize + '"></div>');
        var closeButton = $('<div class="yih4-modal-close">&#215;</div>');

        if (options.closeButton) {
            closeButton.appendTo(modal);
        }

        var modalBg = $('<div class="yih4-modal-bg"></div>');

        modal.appendTo('body');
        modalBg.appendTo('body');

        var videoWidth = modal.width();
        var videoHeight = modal.height();
        var modalWidth = modal.outerWidth();
        var modalHeight = modal.outerHeight();


        if (options.videoId) {
            var iframe = $('<iframe width="'
                + videoWidth
                + '" height="'
                + videoHeight
                + '" src="https://www.youtube.com/embed/'
                + options.videoId
                + '?autoplay=1'
                + '" frameborder="0" allowfullscreen></iframe>');

            iframe.appendTo(modal);
        } else {
            console.error('showYtVideo plugin error: videoId not specified');
        }

        modal.css({
            marginLeft: -modalWidth/2,
            marginTop: -modalHeight/2
        });

        modalBg.css({
            opacity: options.shadowOpacity,
            backgroundColor: options.shadowColor
        });

        closeButton.on('click', function() {
            $(this).parent().fadeOut(350, function() {
                $(this).detach();
                modalBg.detach();
            })
        });

        if (options.clickOutside) {
            $(document).mouseup(function(e) {
                if (!modal.is(e.target) && modal.has(e.target).length === 0) {
                    modal.fadeOut(350, function() {
                        $(this).detach();
                        modalBg.detach();
                    });
                }
            });
        }
    }

});
