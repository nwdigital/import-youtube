jQuery(document).ready(function($){
$('#ajax_post').click(function(event){
    submitForm();
  });
  $('#ajax_post2').click(function(event){
      submitForm();
    });
    $('#ajax_post3').click(function(event){
        submitFormbottom();
      });

function submitForm(){
  var elements = document.querySelectorAll('.ytinput input:checked');
  var values = Array.prototype.map.call(elements, function(el, i){
      return el.value;
  });

  // var yih4_ajax_url = yih4_params.yih4_ajax_url;
  var yih4_ajax_url = yih4_params.yih4_ajax_url;
  var yih4_siteurl = yih4_params.yih4_admurl;

  // dataString = values; // array?
  var jsonString = JSON.stringify(values);
  var listdata = $('.ytinput input:checked').serialize();
  var newdata = $('.ytinput [name="video_list[]"]').serialize();

  $.ajax({

    type: "POST",
    url: yih4_ajax_url,
    data: {
      action: 'yih4_nwd_ajax_create',
      videoID: values
    },

    cache: false,
    beforeSend: function() {
    $('#yih4RSenterkey').html("<img src='" + yih4_siteurl + "/wp-includes/images/spinner-2x.gif' width='20' height='20'/>");
  },

    success: function(){
      $('#yih4RSenterkey').html("");
      $('div.importing').remove();
      fnDisplayAdminMessage('Videos Imported Successfully.', 'green');

    },
  });

};

function submitFormbottom(){
  var elements = document.querySelectorAll('.ytinput input:checked');
  var values = Array.prototype.map.call(elements, function(el, i){
      return el.value;
  });

  // var yih4_ajax_url = yih4_params.yih4_ajax_url;
  var yih4_ajax_url = yih4_params.yih4_ajax_url;
  var yih4_siteurl = yih4_params.yih4_admurl;

  // dataString = values; // array?
  var jsonString = JSON.stringify(values);
  var listdata = $('.ytinput input:checked').serialize();
  var newdata = $('.ytinput [name="video_list[]"]').serialize();

  $.ajax({

    type: "POST",
    url: yih4_ajax_url,
    data: {
      action: 'yih4_nwd_ajax_create',
      videoID: values
    },

    cache: false,
    beforeSend: function() {
    $('#spinner_bottom').html("<img src='" + yih4_siteurl + "/wp-includes/images/spinner-2x.gif' width='20' height='20'/>");
  },

    success: function(){
      $('#spinner_bottom').html("");
      $('div.importing').remove();
      fnDisplayAdminMessage('Videos Imported Successfully.', 'green');

    },
  });

};

function fnDisplayAdminMessage(adminMessage, adminMessageColor) {
  $('html').animate({scrollTop:0}, 'slow');//IE, FF
  $('body').animate({scrollTop:0}, 'slow');//chrome, don't know if Safari works);
        $('#yih4-admin-message').after($('<div class="error notice is-dismissible"><p>' + adminMessage + '</p><button id="yih4-dismiss-admin-message" class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>').hide().fadeIn(2000));
        $("#ajax_post").click(function(event) {
                  $("div.error").fadeTo(100, function() {
                      $("div.error").slideUp(100, function() {
                          $("div.error").remove();
                      });
                  });
              });
              $("#ajax_post2").click(function(event) {
                        $("div.error").fadeTo(100, 0, function() {
                            $("div.error").slideUp(100, function() {
                                $("div.error").remove();
                            });
                        });
                    });
                    $("#ajax_post3").click(function(event) {
                              $("div.error").fadeTo(100, 0, function() {
                                  $("div.error").slideUp(100, function() {
                                      $("div.error").remove();
                                  });
                              });
                          });
        $("#yih4-dismiss-admin-message").click(function(event) {
            event.preventDefault();
            $('.' + 'error').fadeTo(100, 0, function() {
                $('.' + 'error').slideUp(100, function() {
                    $('.' + 'error').remove();
                });
            });
        });
        switch (adminMessageColor) {
        case 'yellow':
            $("div.error").css("border-left", "4px solid #ffba00");
            break;
        case 'red':
            $("div.error").css("border-left", "4px solid #dd3d36");
            break;
        default:
            $("div.error").css("border-left", "4px solid #46b450");
        }
      }
function fnRemoveAdminMessage() {
// check if there is an admin message displayed, if so then remove it
if ($("div.error").length) {
          $("div.error").fadeTo(1000, 0, function() {
              $("div.error").slideUp(1000, function() {
                  $("div.error").remove();
              });
          });
      }
      }

});
